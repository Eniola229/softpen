<?php

namespace App\Http\Controllers\Admin;

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

class SchoolController extends Controller
{
  public function index()
  {
        $schools = School::orderBy('created_at', 'desc')->get();
        return view('admin.school', compact('schools'));

  }  

public function addSchool()
  {
        return view('admin.add-school');

  } 

    public function create(Request $request)
    {
        // Validate the request
        $validatedData = Validator::make($request->all(), [
            'id' => 'nullable',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:schools,email,' . $request->input('id'),
            'mobile' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|min:6',
        ]);

        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }

        // Search by email and update or create
        $admin = School::updateOrCreate(
            ['id' => $request->input('id')],
            [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'address' => $request->input('address'),
            ]
        );

            // Check if the password is provided and hash it
            if ($request->filled('id') && $request->filled('password')) {
                $data['password'] = Hash::make($request->input('password'));
                $school = School::find($request->input('id'));
                // Perform the update

                $school->update($data);
            }

        // Handle avatar upload using Cloudinary
        if ($request->hasFile('avatar')) {
            // If the admin has an existing avatar, delete it from Cloudinary
            if ($admin->image_id) {
                Cloudinary::destroy($admin->image_id);
            }

            // Upload the new avatar to Cloudinary
            $avatarUpload = Cloudinary::upload($request->file('avatar')->getRealPath(), [
                'folder' => 'school_avatars',
            ]);
            
            // Get the image URL and ID from Cloudinary
            $uploadedFileUrl = $avatarUpload->getSecurePath();
            $imageId = $avatarUpload->getPublicId();

            // Update the admin record with the new avatar and image_id
            $admin->update([
                'avatar' => $uploadedFileUrl,
                'image_id' => $imageId, // Store the Cloudinary image ID
            ]);
        }

        // Redirect back with a success message
        return redirect()->back()->with('message', 'School record has been created or updated successfully.');
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

    public function changeStatus($id)
    {   
        $school = School::findOrFail($id);
        // Toggle status based on current value
        if ($school->status == "ACTIVE") {
            $school->update(['status' => 'DISACTIVATE']);
            $message = 'School has been deactivated.';
        } elseif ($school->status == "DISACTIVATE") {
            $school->update(['status' => 'ACTIVE']);
            $message = 'School has been activated.';
        } else {
            $message = "Something seems wrong";
            return redirect()->back()->with('error', $message);
        }

        // Redirect back with a success message
        return redirect()->back()->with('message', $message);
    }


    public function delete($id)
    {
        // Find the admin (school)
        $school = School::findOrFail($id);

        // Check if there is an image associated with the school
        if ($school->image_id) {
            // Delete the image from Cloudinary
            Cloudinary::destroy($school->image_id);
        }

        // Delete the school record from the database
        $school->delete();

        // Redirect with a success message
        return redirect()->back()->with('message', 'School deleted successfully');
    }

}
