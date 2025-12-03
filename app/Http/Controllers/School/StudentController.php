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
use App\Models\Result;
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
            $school = Auth::guard('school')->user();
            $classes = SchClass::where('school_id', $school->id)
                    ->orderBy('created_at', 'desc')->get();
            $departments = Department::where('school_id', $school->id)
                    ->orderBy('created_at', 'desc')->get();
            return view('school.add-student', compact('classes', 'departments'));

    } 

        public function create(Request $request)
    {
        $isUpdate = $request->filled('id');

        // Validation
        $validatedData = Validator::make($request->all(), [
            'id' => 'nullable|exists:students,id',
            'school_id' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:students,email,' . $request->input('id'),
            'address' => 'nullable|string|max:255',
            'age' => 'required|string|max:2',
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
            'age' => $request->input('age'),
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
        $classes = SchClass::where('school_id', $student->school_id)
                    ->orderBy('created_at', 'desc')->get();
        $departments = Department::where('school_id', $student->school_id)
                    ->orderBy('created_at', 'desc')->get();

        $class = $student->class; // e.g., SS2
        $department = $student->department;

        // Fetch all results for this student grouped by session and term
        $results = Result::with('subject')
            ->where('student_id', $student->id)
            ->where('school_id', $student->school_id)
            ->get()
            ->groupBy(['session', 'term']);


       return view('school.view-student', compact('student', 'classes', 'departments', 'results'));
    }

    public function changeStatus($id)
    {   
        $school = Student::findOrFail($id);
        // Toggle status based on current value
        if ($school->status == "ACTIVE") {
            $school->update(['status' => 'DISACTIVATE']);
            $message = 'Student has been deactivated.';
        } elseif ($school->status == "DISACTIVATE") {
            $school->update(['status' => 'ACTIVE']);
            $message = 'Student has been activated.';
        } else {
            $message = "Something seems wrong";
            return redirect()->back()->with('error', $message);
        }

        // Redirect back with a success message
        return redirect()->back()->with('message', $message);
    }

        public function deleteStudent($id)
    {
        // Find the admin (school)
        $student = Student::findOrFail($id);

        // Delete the student record from the database
        $student->delete();

        // Redirect with a success message
        return redirect()->to('school/student')->with('message', 'Student deleted successfully');
    }

    public function promoteAll()
    {
        $school = Auth::guard('school')->user();

        \DB::table('students')
            ->where('school_id', $school->id)
            ->update([
                'class' => \DB::raw("
                    CASE 
                        -- SS
                        WHEN UPPER(class) = 'SS1' THEN 'SS2'
                        WHEN UPPER(class) = 'SS2' THEN 'SS3'
                        WHEN UPPER(class) = 'SS3' THEN 'Graduating-SS'

                        -- JSS
                        WHEN UPPER(class) = 'JSS1' THEN 'JSS2'
                        WHEN UPPER(class) = 'JSS2' THEN 'JSS3'
                        WHEN UPPER(class) = 'JSS3' THEN 'Graduating-JSS'

                        -- BASIC / PRIMARY
                        WHEN UPPER(class) IN ('PRIMARY 1','BASIC 1') THEN 'Primary 2'
                        WHEN UPPER(class) IN ('PRIMARY 2','BASIC 2') THEN 'Primary 3'
                        WHEN UPPER(class) IN ('PRIMARY 3','BASIC 3') THEN 'Primary 4'
                        WHEN UPPER(class) IN ('PRIMARY 4','BASIC 4') THEN 'Primary 5'
                        WHEN UPPER(class) IN ('PRIMARY 5','BASIC 5') THEN 'Primary 6'
                        WHEN UPPER(class) IN ('PRIMARY 6','BASIC 6') THEN 'Graduating-PRIMARY'

                        ELSE class 
                    END
                ")
            ]);

        return response()->json([
            'message' => 'All students in your school have been successfully promoted!'
        ]);
    }


}
