<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\StudentAnswer;
use App\Models\SchClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        return view('student.dashboard');
    }

    public function enterCode(Request $request)
    {
        $request->validate([
            'exam_code' => 'required|string|max:50',
        ], [
            'exam_code.required' => 'Please enter an exam code.',
        ]);

        $student = Auth::guard('student')->user();
        $classId = $this->getClassId($student);

        if (!$classId) {
            return back()->withErrors(['exam_code' => 'Your class could not be found. Please contact your school.']);
        }

        $exam = Exam::where('exam_code', strtoupper(trim($request->exam_code)))
            ->where('school_id', $student->school_id)
            ->where('class_id', $classId)
            ->where('is_published', true)
            ->first();

        if (!$exam) {
            return back()
                ->withInput()
                ->withErrors(['exam_code' => 'Invalid exam code. Please check the code and try again.']);
        }

        // Department check using Subject model
        $subject = Subject::find($exam->subject);
        if ($subject && $subject->department && $subject->department !== $student->department) {
            return back()
                ->withInput()
                ->withErrors(['exam_code' => 'This exam is not available for your department.']);
        }

        // Check if already submitted
        $existingResult = ExamResult::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->whereNotNull('submitted_at')
            ->first();

        if ($existingResult) {
            return back()
                ->withInput()
                ->withErrors(['exam_code' => 'You have already completed this exam.']);
        }

        return redirect()->route('student.exam.start', $exam->id);
    }

    public function startExam($examId)
    {
        $student = Auth::guard('student')->user();
        $classId = $this->getClassId($student);

        $exam = Exam::where('id', $examId)
            ->where('school_id', $student->school_id)
            ->where('class_id', $classId)
            ->where('is_published', true)
            ->firstOrFail();

        $subject = Subject::find($exam->subject);

        // Department guard
        if ($subject && $subject->department && $subject->department !== $student->department) {
            abort(403, 'You are not allowed to take this exam.');
        }

        // Already submitted?
        $existingResult = ExamResult::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->whereNotNull('submitted_at')
            ->first();

        if ($existingResult) {
            return redirect()->route('student-dashboard')
                ->with('error', 'You have already completed this exam.');
        }

        // Create in-progress result record if not yet started
        if (!ExamResult::where('exam_id', $exam->id)->where('student_id', $student->id)->exists()) {
            ExamResult::create([
                'exam_id'    => $exam->id,
                'student_id' => $student->id,
                'started_at' => Carbon::now(),
                'status'     => 'in_progress',
            ]);
        }

        return view('student.exam.exam-start', compact('exam', 'subject'));
    }

    public function takeExam($examId)
    {
        $student = Auth::guard('student')->user();
        $classId = $this->getClassId($student);

        $exam = Exam::with(['questions.options'])
            ->where('id', $examId)
            ->where('school_id', $student->school_id)
            ->where('class_id', $classId)
            ->where('is_published', true)
            ->firstOrFail();

        $subject = Subject::find($exam->subject);

        // Department guard
        if ($subject && $subject->department && $subject->department !== $student->department) {
            abort(403, 'You are not allowed to take this exam.');
        }

        $examResult = ExamResult::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->whereNull('submitted_at')
            ->firstOrFail();

        $now              = $this->getReliableNigeriaTime();
        $startedAt        = Carbon::parse($examResult->started_at, 'Africa/Lagos');
        $elapsedMinutes   = $now->diffInMinutes($startedAt);
        $remainingMinutes = max(0, $exam->duration - $elapsedMinutes);

        // Auto-submit if time is up
        if ($remainingMinutes <= 0) {
            return $this->submitExam(new Request(), $exam->id);
        }

        $questions = $exam->questions;
        if ($exam->randomize_questions) {
            $questions = $questions->shuffle();
        }

        $existingAnswers = StudentAnswer::where('exam_result_id', $examResult->id)
            ->pluck('selected_option_id', 'question_id')
            ->toArray();

        return view('student.exam.take-exam', compact(
            'exam',
            'subject',
            'examResult',
            'questions',
            'existingAnswers',
            'remainingMinutes'
        ));
    }

    public function submitExam(Request $request, $examId)
    {
        $student = Auth::guard('student')->user();
        $classId = $this->getClassId($student);

        $exam = Exam::with(['questions.options'])
            ->where('id', $examId)
            ->where('school_id', $student->school_id)
            ->where('class_id', $classId)
            ->firstOrFail();

        $subject = Subject::find($exam->subject);

        // Department guard
        if ($subject && $subject->department && $subject->department !== $student->department) {
            abort(403, 'You are not allowed to submit this exam.');
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
                    $isCorrect     = $selectedOption->is_correct;
                    $marksObtained = $isCorrect ? $question->mark : 0;
                    $totalScore   += $marksObtained;

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
            'total_score'  => $totalScore,
            'percentage'   => round($percentage, 2),
            'status'       => 'submitted',
        ]);

        if ($exam->show_results) {
            return redirect()->route('student.exam.result', $examResult->id)
                ->with('message', 'Exam submitted successfully!');
        }

        return redirect()->route('student-dashboard')
            ->with('message', 'Exam submitted successfully! Results will be available later.');
    }

    public function showResult($resultId)
    {
        $student = Auth::guard('student')->user();

        $examResult = ExamResult::with(['studentAnswers.question.options', 'studentAnswers.selectedOption'])
            ->where('id', $resultId)
            ->where('student_id', $student->id)
            ->firstOrFail();

        $exam    = Exam::findOrFail($examResult->exam_id);
        $subject = Subject::find($exam->subject);

        if (!$exam->show_results) {
            return redirect()->route('student-dashboard')
                ->with('error', 'Results are not available for this exam.');
        }

        return view('student.exam.exam-result', compact('examResult', 'exam', 'subject'));
    }

    public function saveAnswer(Request $request, $examId)
    {
        $student = Auth::guard('student')->user();

        $examResult = ExamResult::where('exam_id', $examId)
            ->where('student_id', $student->id)
            ->whereNull('submitted_at')
            ->firstOrFail();

        $questionId = $request->input('question_id');
        $optionId   = $request->input('option_id');

        StudentAnswer::updateOrCreate(
            ['exam_result_id' => $examResult->id, 'question_id' => $questionId],
            ['selected_option_id' => $optionId]
        );

        return response()->json(['success' => true]);
    }

    private function getReliableNigeriaTime(): Carbon
    {
        return Carbon::now('Africa/Lagos');
    }
}