<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Option;
use App\Models\SchClass;
use App\Models\School;
use App\Models\Student;
use App\Models\ExamResult;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class QuestionController extends Controller
{
    /**
     * Show form to create new question
     */
    public function create($classId, $examId)
    {
        $staff = Auth::guard('staff')->user();
        
        $class = SchClass::where('id', $classId)
            ->where('school_id', $staff->school_id)
            ->firstOrFail();
            
        $exam = Exam::where('id', $examId)
            ->where('class_id', $classId)
            ->where('school_id', $staff->school_id)
            ->firstOrFail();

        return view('staff.create-question', [
            'class' => $class,
            'exam' => $exam,
        ]);
    }

    /**
     * Store question in database
     */
    public function store(Request $request, $classId, $examId)
    {
        $staff = Auth::guard('staff')->user();
        
        $exam = Exam::where('id', $examId)
            ->where('class_id', $classId)
            ->where('school_id', $staff->school_id)
            ->firstOrFail();
        
        // Validation rules that adapt to question type
        $rules = [
            'question_text' => 'required|string',
            'question_image' => 'nullable|image|max:2048',
            'question_type' => 'required|in:multiple_choice,true_false',
            'mark' => 'required|integer|min:1',
            'acceptTerms' => 'required|accepted',
        ];
        
        // Add options validation only for multiple_choice and true_false
        if ($request->question_type === 'multiple_choice' || $request->question_type === 'true_false') {
            $rules['options'] = 'required|array|min:2';
            $rules['options.*.text'] = 'required|string';
            $rules['options.*.image'] = 'nullable|image|max:2048';
            $rules['correct_option'] = 'required|integer';
        }
        
        $validated = $request->validate($rules);
        
        try {
            // Upload question image if provided
            $questionImage = null;
            if ($request->hasFile('question_image')) {
                $upload = Cloudinary::upload($request->file('question_image')->getRealPath(), [
                    'folder' => 'exam_questions',
                ]);
                $questionImage = $upload->getSecurePath();
            }
            
            // Create question
            $question = Question::create([
                'exam_id' => $examId,
                'question_text' => $validated['question_text'],
                'question_image' => $questionImage,
                'question_type' => $validated['question_type'],
                'order' => Question::where('exam_id', $examId)->max('order') + 1 ?? 1,
                'mark' => $validated['mark'],
            ]);
            
            // Create options for multiple choice and true/false questions
            if (in_array($validated['question_type'], ['multiple_choice', 'true_false']) && isset($validated['options'])) {
                foreach ($validated['options'] as $index => $optionData) {
                    $optionImage = null;
                    
                    // Check if option has file and upload
                    if ($request->hasFile("options.{$index}.image")) {
                        $upload = Cloudinary::upload($request->file("options.{$index}.image")->getRealPath(), [
                            'folder' => 'exam_options',
                        ]);
                        $optionImage = $upload->getSecurePath();
                    }
                    
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $optionData['text'],
                        'option_image' => $optionImage,
                        'is_correct' => $index == $validated['correct_option'],
                        'order' => $index + 1,
                    ]);
                }
            }
            
            return redirect()
                ->route('staff.exams.show', [$classId, $examId])
                ->with('message', 'Question added successfully!');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create question: ' . $e->getMessage());
        }
    }

    /**
     * Show form to edit question
     */
    public function edit($classId, $examId, $questionId)
    {
        $staff = Auth::guard('staff')->user();
        
        $exam = Exam::where('id', $examId)
            ->where('school_id', $staff->school_id)
            ->firstOrFail();
            
        $question = Question::where('id', $questionId)
            ->where('exam_id', $examId)
            ->firstOrFail();
            
        $class = SchClass::find($classId);

        return view('staff.edit-question', [
            'class' => $class,
            'exam' => $exam,
            'question' => $question,
        ]);
    }

    /**
     * Update question in database
     */
    public function update(Request $request, $classId, $examId, $questionId)
    {
        $staff = Auth::guard('staff')->user();
        
        $exam = Exam::where('id', $examId)
            ->where('school_id', $staff->school_id)
            ->firstOrFail();
            
        $question = Question::where('id', $questionId)
            ->where('exam_id', $examId)
            ->firstOrFail();

        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_image' => 'nullable|image|max:2048',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'mark' => 'required|integer|min:1',
        ]);

        try {
            if ($request->hasFile('question_image')) {
                $upload = Cloudinary::upload($request->file('question_image')->getRealPath(), [
                    'folder' => 'exam_questions',
                ]);
                $validated['question_image'] = $upload->getSecurePath();
            }

            $question->update($validated);

            return redirect()
                ->route('staff.exams.show', [$classId, $examId])
                ->with('message', 'Question updated successfully!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update question: ' . $e->getMessage());
        }
    }

    /**
     * Delete question from database
     */
    public function destroy($classId, $examId, $questionId)
    {
        $staff = Auth::guard('staff')->user();
        
        $exam = Exam::where('id', $examId)
            ->where('school_id', $staff->school_id)
            ->firstOrFail();
            
        $question = Question::where('id', $questionId)
            ->where('exam_id', $examId)
            ->firstOrFail();

        try {
            $question->delete();
            
            return redirect()
                ->route('staff.exams.show', [$classId, $examId])
                ->with('message', 'Question deleted successfully!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete question: ' . $e->getMessage());
        }
    }

    public function viewCBTResult($studentId, $resultId)
    {
        $staff = Auth::guard('staff')->user();
        $school = School::findOrFail($staff->school_id);
        
        // Get student
        $student = Student::where('id', $studentId)
            ->where('school_id', $school->id)
            ->firstOrFail();
        
        // Get exam result with all relationships
        $examResult = ExamResult::with([
            'exam.questions.options',
            'studentAnswers.question.options',
            'studentAnswers.selectedOption'
        ])
        ->where('id', $resultId)
        ->where('student_id', $studentId)
        ->whereHas('exam', function($query) use ($school) {
            $query->where('school_id', $school->id);
        })
        ->firstOrFail();
        
        // Get the subject name
        $subject = Subject::find($examResult->exam->subject);
        
        return view('staff.cbt-result-view', compact('student', 'examResult', 'subject'));
    }
}