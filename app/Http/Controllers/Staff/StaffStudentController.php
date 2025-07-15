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

        $classes = SchClass::where('school_id', $school->id)->get();

        return view('staff.class', compact('students', 'classes'));
    }

}
