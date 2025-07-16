<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Staff;
use App\Models\Student;
use App\Models\SchClass;
use App\Models\Department;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use App\Models\Result;

class StaffStudentController extends Controller
{
    public function index()
    {
        $school = School::where('id', Auth::guard('staff')->user()->school_id)->first(); // fetch the school

        if (!$school) {
            abort(404, 'School not found.');
        }

        $students = Student::where('school_id', $school->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $classes = SchClass::where('school_id', $school->id)->orderBy('created_at', 'desc')->get();

        return view('staff.class', compact('students', 'classes'));
    }

    public function students($id)
    {
        $school = School::where('id', Auth::guard('staff')->user()->school_id)->first();

        if (!$school) {
            abort(404, 'School not found.');
        }

        // Fetch the class using $id and make sure it belongs to the current school
        $class = SchClass::where('id', $id)
            ->where('school_id', $school->id)
            ->first();

        if (!$class) {
            abort(404, 'Class not found.');
        }

        $students = Student::where('school_id', $school->id)
            ->where('class', $class->name) // assumes 'class' column stores class name
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff.students', [
            'students' => $students,
            'class' => $class, // pass the specific class, not all classes
        ]);
    }

    public function viewStudent($id)
    {
        $staff = Auth::guard('staff')->user();
        $school = School::findOrFail($staff->school_id);

        $student = Student::where('id', $id)
            ->where('school_id', $school->id)
            ->firstOrFail();

        $class = $student->class; // e.g., SS2
        $department = $student->department; // if applicable

        // Filter subjects for this class and ONLY subjects that match the staff's assigned subject name
        $subjects = Subject::where('school_id', $school->id)
            ->where(function ($query) use ($class) {
                $query->where('for', $class) // exact match (e.g., SS2)
                    ->orWhere('for', substr($class, 0, 2)); // partial match (e.g., SS)
            })
            ->where('name', $staff->subject) // only the subject this staff teaches
            ->get();

        // Fetch all results for this student grouped by session and term
        $results = Result::with('subject')
            ->where('student_id', $student->id)
            ->where('school_id', $school->id)
            ->get()
            ->groupBy(['session', 'term']);


        return view('staff.view-student', compact('student', 'subjects', 'results'));
    }


}
