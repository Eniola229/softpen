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


class DepartmentController extends Controller
{
  public function index()
  {
        $school = Auth::guard('school')->user();
        $departments = Department::where('school_id', $school->id)
                ->orderBy('created_at', 'desc')->get();
        return view('school.departments', compact('departments'));

  }  

    public function addDepartments()
    {     
        return view('school.add-department');
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
        $admin = Department::updateOrCreate(
            ['id' => $request->input('id')],
            [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'department' => $request->input('department'),
                'school_id' => Auth::user()->id,
            ]
        );

        // Redirect back with a success message
        return redirect()->back()->with('message', 'Department record has been created or updated successfully.');
    }

    public function view($id)
    {
        $department = Department::findOrFail($id);

       return view('school.view-department', compact('department'));
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
