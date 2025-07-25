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

class StudentController extends Controller
{
      public function index()
    {
            $school = Auth::guard('school')->user();
            $students = Student::where('school_id', $school->id)
                    ->orderBy('created_at', 'desc')->get();
            return view('school.students', compact('students'));

    }  

       public function addStudent()
    {
            return view('school.add-student');

    } 

        public function create(Request $request)
    {
        $isUpdate = $request->filled('id');

        // Validation
        $validatedData = Validator::make($request->all(), [
            'id' => 'nullable|exists:schools,id',
            'school_id' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:schools,email,' . $request->input('id'),
            'address' => 'nullable|string|max:255',
            'class' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'status' => 'nullable|in:active,inactive',
            'password' => $isUpdate ? 'nullable|min:6' : 'required|min:6',
        ]);

        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }

        $data = [
            'school_id' => Auth::user()->id,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'class' => $request->input('class'),
            'department' => $request->input('department'),
            'status' => $request->input('status', 'active'),
        ];

        if (!$isUpdate || $request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        // Create or update the school
        $school = Student::updateOrCreate(
            ['id' => $request->input('id')],
            $data
        );

        // Handle avatar upload to Cloudinary
        if ($request->hasFile('avatar')) {
            if ($school->image_id) {
                Cloudinary::destroy($school->image_id);
            }

            $upload = Cloudinary::upload($request->file('avatar')->getRealPath(), [
                'folder' => 'school_avatars',
            ]);

            $school->update([
                'avatar' => $upload->getSecurePath(),
                'image_id' => $upload->getPublicId(),
            ]);
        }

        return redirect()->back()->with('message', 'Student record has been created or updated successfully.');
    }

    public function view($id)
    {
        $student = Student::findOrFail($id);

       return view('school.view-student', compact('student'));
    }
}
