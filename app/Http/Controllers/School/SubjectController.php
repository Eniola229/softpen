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


class SubjectController extends Controller
{
  public function index()
  {
        $school = Auth::guard('school')->user();
        $subjects = Subject::where('school_id', $school->id)
                ->orderBy('created_at', 'desc')->get();
        return view('school.subjects', compact('subjects'));

  }  

  public function addSubject()
  { 
        $school = Auth::guard('school')->user();
        $departments = Department::where('school_id', $school->id)->get();
        return view('school.add-subject', compact('departments'));
  } 

    public function create(Request $request)
    {
        // Validate the request
        $validatedData = Validator::make($request->all(), [
            'id' => 'nullable',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',

        ]);

        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }

        // Search by email and update or create
        $admin = Subject::updateOrCreate(
            ['id' => $request->input('id')],
            [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'department' => $request->input('department'),
                'school_id' => Auth::user()->id,
            ]
        );

        // Redirect back with a success message
        return redirect()->back()->with('message', 'Subject record has been created or updated successfully.');
    }

    public function view($id)
    {
       $school = Auth::guard('school')->user();
       $departments = Department::where('school_id', $school->id)->get();
       $subject = Subject::findOrFail($id);
       return view('school.view-subject', compact('subject', 'departments'));
    }

    public function deleteClass($id)
    {
        // Find the admin (school)
        $class = Subject::findOrFail($id);

        // Delete the class record from the database
        $class->delete();

        // Redirect with a success message
        return redirect()->back()->with('message', 'Class deleted successfully');
    }

}
