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
use App\Models\CBT;

class StaffStudentController extends Controller
{
    public function index()
    {
        // Get the staff's school
        $staff = Auth::guard('staff')->user();

        $school = School::find($staff->school_id);

        if (!$school) {
            abort(404, 'School not found.');
        }

        $students = Student::where('school_id', $school->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get only classes that match the staff's assigned classes
        $classes = SchClass::where('school_id', $school->id)
            ->whereIn('name', $staff->class ?? []) 
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff.class', compact('students', 'classes'));
    }


    public function students($id)
    {
        $school = School::where('id', Auth::guard('staff')->user()->school_id)->first();
        $cbt = CBT::where('school_id', $school->id)->first();

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
            'class' => $class,
            'cbt' => $cbt
        ]);
    }

    public function viewStudent($id)
    {
        $staff = Auth::guard('staff')->user();
        $school = School::findOrFail($staff->school_id);

        $student = Student::where('id', $id)
            ->where('school_id', $school->id)
            ->firstOrFail();

        $class = $student->class;
        $department = $student->department;

        // Decode staff subjects (IDs)
        $staffSubjects = is_array($staff->subject)
            ? $staff->subject
            : json_decode($staff->subject, true);

        if (!$staffSubjects) {
            $staffSubjects = [];
        }

        // Fetch subjects that:
        // 1. match student class (SS2 or SS)
        // 2. are taught by the teacher (by ID)
        // 3. match department or are general
        $subjects = Subject::where('school_id', $school->id)
            ->where(function ($query) use ($class) {
                $query->where('for', $class)
                      ->orWhere('for', substr($class, 0, 2)); // SS or JSS
            })
            ->whereIn('id', $staffSubjects) // Teacher is assigned to these subjects
            ->where(function ($query) use ($department) {
                $query->whereNull('department') // general subjects
                      ->orWhere('department', '') // empty department
                      ->orWhere('department', $department); // student dept subjects
            })
            ->get();

        // Fetch results
        $results = Result::where('student_id', $student->id)
            ->where('school_id', $school->id)
            ->get()
            ->groupBy(['session', 'term']);

        return view('staff.view-student', compact('student', 'subjects', 'results', 'staffSubjects'));
    }




}
