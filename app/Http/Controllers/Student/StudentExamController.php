<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\StudentAnswer;
use App\Models\SchClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class StudentExamController extends Controller
{

    protected function getClassId($student)
    {
        $studentClass = SchClass::where('school_id', $student->school_id)
            ->where('name', $student->class)
            ->first();

        if (!$studentClass) {
            return null;
        }

        return $studentClass->id;
    }

    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        $classId = $this->getClassId($student);

        dd(Carbon::now()->setTimezone('Africa/Lagos'));

        if (!$classId) {
            return redirect()->route('student-dashboard')
                ->with('error', 'Your class was not found.');
        }
        
        $now = $this->getNigeriaOnlineTime();

        $allExams = Exam::with('subject')
            ->where('school_id', $student->school_id)
            ->where('class_id', $classId)
            ->where('is_published', true)
            ->get();

        $activeExam = $allExams->filter(function($exam) use ($student, $now) {
            if (is_object($exam->subject) && property_exists($exam->subject, 'department') && $exam->subject->department) {
                if ($exam->subject->department !== $student->department) {
                    return false;
                }
            }

            $examDateTime = Carbon::parse($exam->exam_date_time);
            $startWindow = $examDateTime->copy()->subMinutes(10);
            $endWindow = $examDateTime->copy()->addMinutes((int) $exam->duration);

            $isWithinWindow = $now->greaterThanOrEqualTo($startWindow) && $now->lessThanOrEqualTo($endWindow);

            if (!$isWithinWindow) {
                return false;
            }

            $result = ExamResult::where('exam_id', $exam->id)
                ->where('student_id', $student->id)
                ->whereNotNull('submitted_at')
                ->first();

            return !$result;
        })->first();

        if ($activeExam) {
            if ($now->gt(Carbon::parse($activeExam->exam_date_time)->addMinutes(10))) {
                return view('student.exam.exam-late', compact('activeExam'));
            }

            return redirect()->route('student.exam.start', $activeExam->id);
        }

        return view('student.dashboard');
    }

    public function startExam($examId)
    {
        $student = Auth::guard('student')->user();
        $classId = $this->getClassId($student);

        $exam = Exam::with('subject')
            ->where('id', $examId)
            ->where('school_id', $student->school_id)
            ->where('class_id', $classId)
            ->where('is_published', true)
            ->firstOrFail();

        if (is_object($exam->subject) && property_exists($exam->subject, 'department') && $exam->subject->department) {
            if ($exam->subject->department !== $student->department) {
                abort(403, 'You are not allowed to take this exam.');
            }
        }

        $now = $this->getNigeriaOnlineTime();
        $examDateTime = Carbon::parse($exam->exam_date_time);
        $startWindow = $examDateTime->copy()->subMinutes(10);
        $endWindow = $examDateTime->copy()->addMinutes((int) $exam->duration);

        $isWithinWindow = $now->greaterThanOrEqualTo($startWindow) && $now->lessThanOrEqualTo($endWindow);

        if (!$isWithinWindow) {
            return redirect()->route('student-dashboard')
                ->with('error', 'This exam is not currently available.');
        }

        $existingResult = ExamResult::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingResult && $existingResult->submitted_at) {
            return redirect()->route('student-dashboard')
                ->with('error', 'You have already completed this exam.');
        }

        if (!$existingResult) {
            $existingResult = ExamResult::create([
                'exam_id' => $exam->id,
                'student_id' => $student->id,
                'started_at' => Carbon::now(),
                'status' => 'in_progress',
            ]);
        }

        return view('student.exam.exam-start', compact('exam', 'existingResult'));
    }
    public function takeExam($examId)
    {
        $student = Auth::guard('student')->user();
        $classId = $this->getClassId($student);

        $exam = Exam::with(['questions.options', 'subject'])
            ->where('id', $examId)
            ->where('school_id', $student->school_id)
            ->where('class_id', $classId)
            ->where('is_published', true)
            ->firstOrFail();

        if (is_object($exam->subject) && property_exists($exam->subject, 'department') && $exam->subject->department) {
            if ($exam->subject->department !== $student->department) {
                abort(403, 'You are not allowed to take this exam.');
            }
        }

        $examResult = ExamResult::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->whereNull('submitted_at')
            ->firstOrFail();

        // Use reliable server time instead of cached API time
        $now = $this->getReliableNigeriaTime();
        $endTime = Carbon::parse($exam->exam_date_time, 'Africa/Lagos')->addMinutes((int) $exam->duration);
        
        if ($now->gt($endTime)) {
            return $this->submitExam(new Request(), $exam->id);
        }

        $questions = $exam->questions;
        if ($exam->randomize_questions) {
            $questions = $questions->shuffle();
        }

        $existingAnswers = StudentAnswer::where('exam_result_id', $examResult->id)
            ->pluck('selected_option_id', 'question_id')
            ->toArray();

        $startedAt = Carbon::parse($examResult->started_at, 'Africa/Lagos');
        $elapsedMinutes = $now->diffInMinutes($startedAt);
        $remainingMinutes = max(0, $exam->duration - $elapsedMinutes);

        return view('student.exam.take-exam', compact('exam', 'examResult', 'questions', 'existingAnswers', 'remainingMinutes'));
    }

    public function submitExam(Request $request, $examId)
    {
        $student = Auth::guard('student')->user();
        $classId = $this->getClassId($student);

        $exam = Exam::with(['questions.options', 'subject'])
            ->where('id', $examId)
            ->where('school_id', $student->school_id)
            ->where('class_id', $classId)
            ->firstOrFail();

        if (is_object($exam->subject) && property_exists($exam->subject, 'department') && $exam->subject->department) {
            if ($exam->subject->department !== $student->department) {
                abort(403, 'You are not allowed to submit this exam.');
            }
        }

        $examResult = ExamResult::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->whereNull('submitted_at')
            ->firstOrFail();

        $totalScore = 0;
        $totalMarks = 0;

        foreach ($exam->questions as $question) {
            $totalMarks += $question->mark;
            
            $answerId = $request->input("answers.{$question->id}", $request->input("question_{$question->id}"));

            if ($answerId) {
                $selectedOption = $question->options()->find($answerId);
                if ($selectedOption) {
                    $isCorrect = $selectedOption->is_correct;
                    $marksObtained = $isCorrect ? $question->mark : 0;
                    $totalScore += $marksObtained;

                    StudentAnswer::updateOrCreate(
                        ['exam_result_id' => $examResult->id, 'question_id' => $question->id],
                        ['selected_option_id' => $answerId, 'is_correct' => $isCorrect, 'marks_obtained' => $marksObtained]
                    );
                }
            } else {
                StudentAnswer::updateOrCreate(
                    ['exam_result_id' => $examResult->id, 'question_id' => $question->id],
                    ['selected_option_id' => null, 'is_correct' => false, 'marks_obtained' => 0]
                );
            }
        }

        $percentage = $totalMarks > 0 ? ($totalScore / $totalMarks) * 100 : 0;

        $examResult->update([
            'submitted_at' => Carbon::now(),
            'total_score' => $totalScore,
            'percentage' => round($percentage, 2),
            'status' => 'submitted',
        ]);

        if ($exam->show_results) {
            return redirect()->route('student.exam.result', $examResult->id)
                ->with('message', 'Exam submitted successfully!');
        } else {
            return redirect()->route('student-dashboard')
                ->with('message', 'Exam submitted successfully! Results will be available later.');
        }
    }

    public function showResult($resultId)
    {
        $student = Auth::guard('student')->user();

        $examResult = ExamResult::with(['exam', 'studentAnswers.question.options', 'studentAnswers.selectedOption'])
            ->where('id', $resultId)
            ->where('student_id', $student->id)
            ->firstOrFail();

        if (!$examResult->exam->show_results) {
            return redirect()->route('student-dashboard')
                ->with('error', 'Results are not available for this exam.');
        }

        return view('student.exam.exam-result', compact('examResult'));
    }

    public function saveAnswer(Request $request, $examId)
    {
        $student = Auth::guard('student')->user();
        $classId = $this->getClassId($student);

        $examResult = ExamResult::where('exam_id', $examId)
            ->where('student_id', $student->id)
            ->whereNull('submitted_at')
            ->firstOrFail();

        $questionId = $request->input('question_id');
        $optionId = $request->input('option_id');

        StudentAnswer::updateOrCreate(
            ['exam_result_id' => $examResult->id, 'question_id' => $questionId],
            ['selected_option_id' => $optionId]
        );

        return response()->json(['success' => true]);
    }

private function getNigeriaOnlineTime(): Carbon
{
    return Carbon::now();
}


    private function getReliableNigeriaTime(): Carbon
    {
        // For exam timing, use server time set to Nigeria timezone
        // This is more reliable than external API calls
        return Carbon::now('Africa/Lagos');
    }
}