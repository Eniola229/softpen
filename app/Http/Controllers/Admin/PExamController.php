<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PClass;
use App\Models\PExam;
use App\Models\Subject;
use App\Models\Department;
use Illuminate\Http\Request;

class PExamController extends Controller
{
    public function index($classId)
    {
        $class = PClass::findOrFail($classId);
        $exams = PExam::where('p_class_id', $classId)
            ->with('questions')
            ->paginate(10);
        
        $subjects = Subject::all()->keyBy('id');

        return view('admin.practice.exams.index', compact('class', 'exams', 'subjects'));
    }

    public function create($classId)
    {
        $class = PClass::findOrFail($classId);
        $subjects = Subject::all();
        $departments = Department::all();

        return view('admin.practice.exams.create', compact('class', 'subjects', 'departments'));
    }

    public function store(Request $request, $classId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string',
            'department' => 'nullable|string',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'total_questions' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'session' => 'required|string',
            'term' => 'required|string',
            'randomize_questions' => 'nullable|boolean',
            'show_one_question_at_time' => 'nullable|boolean',
            'show_results' => 'nullable|boolean',
            'show_explanations' => 'nullable|boolean',
            'instructions' => 'nullable|string',
        ]);

        $validated['p_class_id'] = $classId;
        $validated['randomize_questions'] = $request->has('randomize_questions');
        $validated['show_one_question_at_time'] = $request->has('show_one_question_at_time');
        $validated['show_results'] = $request->has('show_results');
        $validated['show_explanations'] = $request->has('show_explanations');
        $validated['department_id'] = $request->department;

        PExam::create($validated);

        return redirect()->route('admin.practice.exams.index', $classId)
            ->with('message', 'Practice exam created successfully!');
    }

    public function show($classId, $examId)
    {
        $class = PClass::findOrFail($classId);
        $exam = PExam::with('questions.options')->findOrFail($examId);
        $questions = $exam->questions()->orderBy('order')->get();
        $subject = Subject::find($exam->subject);
        $department = Department::find($exam->department_id);

        return view('admin.practice.exams.show', compact('class', 'exam', 'questions', 'subject', 'department'));
    }

    public function edit($classId, $examId)
    {
        $class = PClass::findOrFail($classId);
        $exam = PExam::findOrFail($examId);
        $subjects = Subject::all();
        $departments = Department::all();

        return view('admin.practice.exams.edit', compact('class', 'exam', 'subjects', 'departments'));
    }

    public function update(Request $request, $classId, $examId)
    {
        $exam = PExam::findOrFail($examId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string',
            'department' => 'nullable|string',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'total_questions' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'session' => 'required|string',
            'term' => 'required|string',
            'randomize_questions' => 'nullable|boolean',
            'show_one_question_at_time' => 'nullable|boolean',
            'show_results' => 'nullable|boolean',
            'show_explanations' => 'nullable|boolean',
            'instructions' => 'nullable|string',
        ]);

        $validated['randomize_questions'] = $request->has('randomize_questions');
        $validated['show_one_question_at_time'] = $request->has('show_one_question_at_time');
        $validated['show_results'] = $request->has('show_results');
        $validated['show_explanations'] = $request->has('show_explanations');
        $validated['department_id'] = $request->department;

        $exam->update($validated);

        return redirect()->route('admin.practice.exams.show', [$classId, $examId])
            ->with('message', 'Practice exam updated successfully!');
    }

    public function destroy($classId, $examId)
    {
        $exam = PExam::findOrFail($examId);
        $exam->delete();

        return redirect()->route('admin.practice.exams.index', $classId)
            ->with('message', 'Practice exam deleted successfully!');
    }

    public function publish($classId, $examId)
    {
        $exam = PExam::findOrFail($examId);
        
        if ($exam->questions()->count() < $exam->total_questions) {
            return redirect()->back()->with('error', 'Cannot publish exam. Please add all required questions first.');
        }

        $exam->update(['is_published' => true]);

        return redirect()->route('admin.practice.exams.show', [$classId, $examId])
            ->with('message', 'Practice exam published successfully!');
    }
}