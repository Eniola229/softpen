<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\School;
use App\Models\Staff;
use App\Models\Student;
use App\Models\SchClass;
use App\Models\Department;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;


class ClassController extends Controller
{
  public function index()
  {
        $school = Auth::guard('school')->user();
        $classes = SchClass::where('school_id', $school->id)
                ->orderBy('created_at', 'desc')->get();
        return view('school.class', compact('classes'));

  }  

public function addClass()
  {
        return view('school.add-class');

  } 

    public function create(Request $request)
    {
        // Validate the request
        $validatedData = Validator::make($request->all(), [
            'id' => 'nullable',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',

        ]);

        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }

        // Search by email and update or create
        $admin = SchClass::updateOrCreate(
            ['id' => $request->input('id')],
            [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'school_id' => Auth::user()->id,
            ]
        );

        // Redirect back with a success message
        return redirect()->back()->with('message', 'Class record has been created or updated successfully.');
    }

    public function view($id)
    {
        $school = School::findOrFail($id);
        $staffs = Staff::where('school_id', $school->id)->get();
        $students = Student::where('school_id', $school->id)->get();
        $classes = SchClass::where('school_id', $school->id)->get();
        $subjects = Subject::where('school_id', $school->id)->get();
        $departments = Department::where('school_id', $school->id)->get();

       return view('admin.view-school', compact('school', 'staffs', 'students', 'classes', 'subjects', 'departments'));
    }

    public function deleteClass($id)
    {
        // Find the admin (school)
        $class = SchClass::findOrFail($id);

        // Delete the class record from the database
        $class->delete();

        // Redirect with a success message
        return redirect()->back()->with('message', 'Class deleted successfully');
    }

}
