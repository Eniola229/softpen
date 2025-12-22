<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PClass;
use App\Models\PExam;
use App\Models\PQuestion;
use App\Models\PQuestionOption;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PQuestionController extends Controller
{
    public function create($classId, $examId)
    {
        $class = PClass::findOrFail($classId);
        $exam = PExam::findOrFail($examId);
        $nextOrder = $exam->questions()->max('order') + 1;

        return view('admin.practice.questions.create', compact('class', 'exam', 'nextOrder'));
    }

    public function store(Request $request, $classId, $examId)
    {
        $exam = PExam::findOrFail($examId);

        if ($exam->questions()->count() >= $exam->total_questions) {
            return redirect()->back()->with('error', 'Maximum number of questions reached for this exam.');
        }

        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'mark' => 'required|integer|min:1',
            'order' => 'required|integer|min:1',
            'explanation' => 'required|string',
            'hint' => 'nullable|string',
            'options' => 'required_if:question_type,multiple_choice,true_false|array|min:2',
            'options.*.option_text' => 'required_with:options|string',
            'options.*.is_correct' => 'nullable|boolean',
            'options.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload question image to Cloudinary
        $imagePath = null;
        if ($request->hasFile('question_image')) {
            $upload = Cloudinary::upload($request->file('question_image')->getRealPath(), [
                'folder' => 'practice_questions',
            ]);
            $imagePath = $upload->getSecurePath();
        }

        $question = PQuestion::create([
            'p_exam_id' => $examId,
            'question_text' => $validated['question_text'],
            'question_image' => $imagePath,
            'question_type' => $validated['question_type'],
            'mark' => $validated['mark'],
            'order' => $validated['order'],
            'explanation' => $validated['explanation'],
            'hint' => $validated['hint'],
        ]);

        // Create options with Cloudinary image support
        if (in_array($validated['question_type'], ['multiple_choice', 'true_false'])) {
            foreach ($request->options as $index => $option) {
                $optionImage = null;
                
                if ($request->hasFile("options.{$index}.image")) {
                    $upload = Cloudinary::upload($request->file("options.{$index}.image")->getRealPath(), [
                        'folder' => 'practice_options',
                    ]);
                    $optionImage = $upload->getSecurePath();
                }

                PQuestionOption::create([
                    'p_question_id' => $question->id,
                    'option_text' => $option['option_text'],
                    'option_image' => $optionImage,
                    'is_correct' => isset($option['is_correct']) && $option['is_correct'] == '1',
                ]);
            }
        }

        return redirect()->route('admin.practice.exams.show', [$classId, $examId])
            ->with('message', 'Practice question added successfully!');
    }

    public function edit($classId, $examId, $questionId)
    {
        $class = PClass::findOrFail($classId);
        $exam = PExam::findOrFail($examId);
        $question = PQuestion::with('options')->findOrFail($questionId);

        return view('admin.practice.questions.edit', compact('class', 'exam', 'question'));
    }

    public function update(Request $request, $classId, $examId, $questionId)
    {
        $question = PQuestion::findOrFail($questionId);

        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'mark' => 'required|integer|min:1',
            'order' => 'required|integer|min:1',
            'explanation' => 'required|string',
            'hint' => 'nullable|string',
            'options' => 'required_if:question_type,multiple_choice,true_false|array|min:2',
            'options.*.option_text' => 'required_with:options|string',
            'options.*.is_correct' => 'nullable|boolean',
            'options.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update question image if new one uploaded
        $imagePath = $question->question_image;
        if ($request->hasFile('question_image')) {
            $upload = Cloudinary::upload($request->file('question_image')->getRealPath(), [
                'folder' => 'practice_questions',
            ]);
            $imagePath = $upload->getSecurePath();
        }

        $question->update([
            'question_text' => $validated['question_text'],
            'question_image' => $imagePath,
            'question_type' => $validated['question_type'],
            'mark' => $validated['mark'],
            'order' => $validated['order'],
            'explanation' => $validated['explanation'],
            'hint' => $validated['hint'],
        ]);

        // Update options
        if (in_array($validated['question_type'], ['multiple_choice', 'true_false'])) {
            $question->options()->delete();
            
            foreach ($request->options as $index => $option) {
                $optionImage = null;
                
                if ($request->hasFile("options.{$index}.image")) {
                    $upload = Cloudinary::upload($request->file("options.{$index}.image")->getRealPath(), [
                        'folder' => 'practice_options',
                    ]);
                    $optionImage = $upload->getSecurePath();
                }

                PQuestionOption::create([
                    'p_question_id' => $question->id,
                    'option_text' => $option['option_text'],
                    'option_image' => $optionImage,
                    'is_correct' => isset($option['is_correct']) && $option['is_correct'] == '1',
                ]);
            }
        }

        return redirect()->route('admin.practice.exams.show', [$classId, $examId])
            ->with('message', 'Practice question updated successfully!');
    }

    public function destroy($classId, $examId, $questionId)
    {
        $question = PQuestion::findOrFail($questionId);
        $question->delete();

        return redirect()->route('admin.practice.exams.show', [$classId, $examId])
            ->with('message', 'Practice question deleted successfully!');
    }
}