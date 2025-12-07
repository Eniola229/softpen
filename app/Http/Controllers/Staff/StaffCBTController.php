<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CBT;
use App\Models\Result;
use App\Models\SchClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\School;
use App\Models\Exam;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class StaffCBTController extends Controller
{
    public function index($classId)
    {
        $school = School::where('id', Auth::guard('staff')->user()->school_id)->first();
        
        // Check if CBT is active
        $cbt = CBT::where('school_id', $school->id)->first();
        if (!$cbt || $cbt->status !== 'ACTIVE') {
            return back()->with('error', 'CBT is not active');
        }

        // Get the class
        $class = SchClass::where('id', $classId)
            ->where('school_id', $school->id)
            ->firstOrFail();

        // Get teacher's subjects
        $staff = Auth::guard('staff')->user();
        $teacherSubjects = json_decode($staff->subjects ?? '[]', true);

        // Get exams for this school and class
        $exams = Exam::where('school_id', $school->id)
            ->where('class_id', $classId)
            ->orderBy('created_at', 'desc');
        
        // Filter by teacher's subjects if they have any
        if (!empty($teacherSubjects)) {
            $exams = $exams->whereIn('subject', $teacherSubjects);
        }
        
        $exams = $exams->paginate(10);

        // Get all subject UUIDs from exams
        $subjectUuids = $exams->pluck('subject')->filter()->unique()->toArray();
        
        // Load all subjects at once
        $subjects = Subject::whereIn('id', $subjectUuids)->get()->keyBy('id');
        
        // Attach subject names to exams for easy access in view
        foreach ($exams as $exam) {
            if ($exam->subject && isset($subjects[$exam->subject])) {
                $exam->subject_name = $subjects[$exam->subject]->name;
            } else {
                $exam->subject_name = 'No Subject';
            }
        }

        return view('staff.exams', [
            'exams' => $exams,
            'school' => $school,
            'class' => $class,
            'cbt' => $cbt,
            'subjects' => $subjects, 
        ]);
    }
}
