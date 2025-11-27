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

class TeachersController extends Controller
{
      public function index()
    {
            $school = Auth::guard('school')->user();
            $teachers = Staff::where('school_id', $school->id)
                    ->orderBy('created_at', 'desc')->get();
            return view('school.teachers', compact('teachers'));

    }  

       public function addStaff()
    {
            $school = Auth::guard('school')->user();
            $subjects = Subject::where('school_id', $school->id)->orderBy('created_at', 'desc')->get();
            $depts = Department::where('school_id', $school->id)->orderBy('created_at', 'desc')->get();
            $classes = SchClass::where('school_id', $school->id)->orderBy('created_at', 'desc')->get();
            return view('school.add-teacher', compact('subjects', 'depts', 'classes'));

    } 

    public function create(Request $request)
    {
        $isUpdate = $request->filled('id');

        // Validation
        $validatedData = Validator::make($request->all(), [
            'id' => 'nullable|exists:staffs,id',
            'school_id' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:staffs,email,' . $request->input('id'),
            'mobile' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'class' => 'nullable|array',       // now array for multi-select
            'department' => 'nullable|string',
            'subject' => 'nullable|array',     // now array for multi-select
            'status' => 'nullable|in:active,inactive',
            'password' => $isUpdate ? 'nullable|min:6' : 'required|min:6',
        ]);

        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }

        // Prepare data
        $data = [
            'school_id'   => Auth::user()->id,
            'name'        => $request->input('name'),
            'email'       => $request->input('email'),
            'mobile'      => $request->input('mobile'),
            'address'     => $request->input('address'),
            'class'       => $request->input('class', []),      // save as array
            'department'  => $request->input('department', []), // save as array
            'subject'     => $request->input('subject', []),    // save as array
            'status'      => $request->input('status', 'active'),
        ];

        // Hash password if new or updated
        if (!$isUpdate || $request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        // Ensure your Staff model casts arrays properly
        // protected $casts = [
        //     'class' => 'array',
        //     'department' => 'array',
        //     'subject' => 'array',
        // ];

        // Create or update staff record
        $staff = Staff::updateOrCreate(
            ['id' => $request->input('id')],
            $data
        );

        // Handle avatar upload to Cloudinary
        if ($request->hasFile('avatar')) {
            if ($staff->image_id) {
                Cloudinary::destroy($staff->image_id);
            }

            $upload = Cloudinary::upload($request->file('avatar')->getRealPath(), [
                'folder' => 'staff_avatars',
            ]);

            $staff->update([
                'avatar'   => $upload->getSecurePath(),
                'image_id' => $upload->getPublicId(),
            ]);
        }

        return redirect()->back()->with('message', 'Teacher record has been created or updated successfully.');
    }

    public function view($id)
    {
        $teacher = Staff::findOrFail($id);
        $school = Auth::guard('school')->user();

        // Decode teacher subject IDs
        $subjectIds = is_array($teacher->subject)
            ? $teacher->subject
            : json_decode($teacher->subject, true);

        // Fetch subjects that match these IDs
        $subjectNames = Subject::whereIn('id', $subjectIds)->pluck('name')->toArray();

        $subjects = Subject::where('school_id', $school->id)->orderBy('created_at', 'desc')->get();
        $depts    = Department::where('school_id', $school->id)->orderBy('created_at', 'desc')->get();
        $classes  = SchClass::where('school_id', $school->id)->orderBy('created_at', 'desc')->get();

        return view('school.view-teacher', compact('teacher', 'subjects', 'depts', 'classes', 'subjectNames'));
    }

    public function changeStatus($id)
    {   
        $school = Staff::findOrFail($id);
        // Toggle status based on current value
        if ($school->status == "ACTIVE") {
            $school->update(['status' => 'DISACTIVATE']);
            $message = 'Teacher has been deactivated.';
        } elseif ($school->status == "DISACTIVATE") {
            $school->update(['status' => 'ACTIVE']);
            $message = 'Teacher has been activated.';
        } else {
            $message = "Something seems wrong";
            return redirect()->back()->with('error', $message);
        }

        // Redirect back with a success message
        return redirect()->back()->with('message', $message);
    }


}
