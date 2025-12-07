<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\SchClass;
use App\Models\Subject;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ExamController extends Controller
{
    /**
     * Show the form for creating a new exam
     */
    public function create($classId)
    {
        $staff = Auth::guard('staff')->user();
        
        $class = SchClass::where('id', $classId)
            ->where('school_id', $staff->school_id)
            ->firstOrFail();

        // Decode staff subject IDs
        $subjectIds = is_array($staff->subject)
            ? $staff->subject
            : json_decode($staff->subject, true);
        
        // Fetch subjects that match these IDs
        $subjects = Subject::whereIn('id', $subjectIds)->get();
        $departments = Department::where('school_id', $staff->school_id)->get();

        return view('staff.create-exam', [
            'class' => $class,
            'subjects' => $subjects,
            'departments' => $departments,
        ]);
    }

    /**
     * Store the newly created exam in database
     */
    public function store(Request $request, $classId)
    {
        // Get authenticated staff
        $staff = Auth::guard('staff')->user();
        
        // Get the class and verify it belongs to staff's school
        $class = SchClass::where('id', $classId)
            ->where('school_id', $staff->school_id)
            ->firstOrFail();

        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string',
            'department' => 'nullable|string',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'total_questions' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'exam_date_time' => 'required|date_format:Y-m-d\TH:i',
            'instructions' => 'nullable|string',
            'randomize_questions' => 'nullable|boolean',
            'show_one_question_at_time' => 'nullable|boolean',
            'show_results' => 'nullable|boolean',
            'acceptTerms' => 'required|accepted',
            'session' => 'required|string',
            'term' => 'required|string',
        ]);

        try {
            // Create the exam
            $exam = Exam::create([
                'school_id' => $staff->school_id,
                'class_id' => $classId,
                'title' => $validated['title'],
                'subject' => $validated['subject'],
                'description' => $validated['description'] ?? null,
                'duration' => $validated['duration'],
                'total_questions' => $validated['total_questions'],
                'passing_score' => $validated['passing_score'],
                'exam_date_time' => $validated['exam_date_time'],
                'instructions' => $validated['instructions'] ?? null,
                'department' => $validated['department'] ?? null,
                'randomize_questions' => $validated['randomize_questions'] ?? false,
                'show_one_question_at_time' => $validated['show_one_question_at_time'] ?? false,
                'show_results' => $validated['show_results'] ?? true,
                'session' => $validated['session'],
                'term' => $validated['term'],
                'is_published' => false,
            ]);

            return redirect()
                ->route('staff.exams.show', [$classId, $exam->id])
                ->with('message', 'Exam created successfully! Now add questions to your exam.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create exam: ' . $e->getMessage());
        }
    }

    /**
     * Show the exam details
     */
    public function show($classId, $examId)
    {
        $staff = Auth::guard('staff')->user();
        
        $class = SchClass::where('id', $classId)
            ->where('school_id', $staff->school_id)
            ->firstOrFail();

        $exam = Exam::where('id', $examId)
            ->where('class_id', $classId)
            ->where('school_id', $staff->school_id)
            ->firstOrFail();

        $questions = $exam->questions()->orderBy('order')->get();
        $subject = Subject::where('id', $exam->subject)->first();
        $department = Department::where('id', $exam->department)->first();


        return view('staff.exam-details', [
            'class' => $class,
            'exam' => $exam,
            'questions' => $questions,
            'subject' => $subject,
            'department' => $department,
        ]);
    }

    /**
     * Show the form for editing an exam
     */
    public function edit($classId, $examId)
    {
        $staff = Auth::guard('staff')->user();
        
        $class = SchClass::where('id', $classId)
            ->where('school_id', $staff->school_id)
            ->firstOrFail();

        $exam = Exam::where('id', $examId)
            ->where('class_id', $classId)
            ->where('school_id', $staff->school_id)
            ->firstOrFail();

        // Decode staff subject IDs
        $subjectIds = is_array($staff->subject)
            ? $staff->subject
            : json_decode($staff->subject, true);
        
        // Fetch subjects that match these IDs
        $subjects = Subject::whereIn('id', $subjectIds)->get();
        $departments = Department::where('school_id', $staff->school_id)->get();

        return view('staff.edit-exam', [
            'class' => $class,
            'exam' => $exam,
            'subjects' => $subjects,
            'departments' => $departments,
        ]);
    }

    /**
     * Update the exam in database
     */
    public function update(Request $request, $classId, $examId)
    {
        $staff = Auth::guard('staff')->user();
        
        $exam = Exam::where('id', $examId)
            ->where('class_id', $classId)
            ->where('school_id', $staff->school_id)
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'total_questions' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'instructions' => 'nullable|string',
            'department' => 'nullable|string',
            'randomize_questions' => 'nullable|boolean',
            'show_one_question_at_time' => 'nullable|boolean',
            'show_results' => 'nullable|boolean',
            'session' => 'required|string',
            'term' => 'required|string',
            'exam_date_time' => 'required|date_format:Y-m-d\TH:i',
        ]);

        try {
            $exam->update($validated);

            return redirect()
                ->route('staff.exams.show', [$classId, $exam->id])
                ->with('message', 'Exam updated successfully!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update exam: ' . $e->getMessage());
        }
    }

    /**
     * Delete the exam
     */
    public function destroy($classId, $examId)
    {
        $staff = Auth::guard('staff')->user();
        
        $exam = Exam::where('id', $examId)
            ->where('class_id', $classId)
            ->where('school_id', $staff->school_id)
            ->firstOrFail();

        try {
            $exam->delete();

            return redirect()
                ->back()
                ->with('message', 'Exam deleted successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete exam: ' . $e->getMessage());
        }
    }


    public function publish($classId, $examId)
    {
        $staff = Auth::guard('staff')->user();
        
        // Find the exam with validation
        $exam = Exam::where('id', $examId)
            ->where('class_id', $classId)
            ->where('school_id', $staff->school_id)
            ->firstOrFail();
        
        // Check if exam already published
        if ($exam->is_published) {
            return redirect()
                ->route('staff.exams.show', [$classId, $examId])
                ->with('error', 'This exam is already published.');
        }
        
        // Check if exam has required number of questions
        $questionCount = $exam->questions()->count();
        if ($questionCount < $exam->total_questions) {
            return redirect()
                ->route('staff.exams.show', [$classId, $examId])
                ->with('error', "Cannot publish exam. You need {$exam->total_questions} questions but only have {$questionCount}.");
        }
        
        // Update the exam to published
        $exam->is_published = true;
        $exam->save();
        
        return redirect()
            ->route('staff.exams.show', [$classId, $examId])
            ->with('message', 'Exam published successfully! Students can now take this exam.');
    }
}