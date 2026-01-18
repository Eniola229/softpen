<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PClass;
use App\Models\Subject;
use Illuminate\Http\Request;

class PClassController extends Controller
{
    public function index()
    {
        $classes = PClass::paginate(100);
        return view('admin.practice.classes.index', compact('classes'));
    }

    public function create()
    {
        $subjects = Subject::all();
        return view('admin.practice.classes.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string',
        ]);

        PClass::create($validated);

        return redirect()->route('classes.index')
            ->with('message', 'Practice class created successfully!');
    }

    public function show($id)
    {
        $class = PClass::findOrFail($id);
        return redirect()->route('exams.index', $class->id);
    }

    public function edit($id)
    {
        $class = PClass::findOrFail($id);
        $subjects = Subject::all();
        return view('admin.practice.classes.edit', compact('class', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        $class = PClass::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string',
        ]);

        $class->update($validated);

        return redirect()->route('classes.index')
            ->with('message', 'Practice class updated successfully!');
    }

    public function destroy($id)
    {
        $class = PClass::findOrFail($id);
        $class->delete();

        return redirect()->route('classes.index')
            ->with('message', 'Practice class deleted successfully!');
    }
}