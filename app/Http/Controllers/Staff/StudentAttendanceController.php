<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Attendance;
use App\Models\SchClass;
use App\Models\School;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StudentAttendanceController extends Controller
{
    public function index($classId)
    {
        $staff = auth()->guard('staff')->user();
        $school = School::where('id', $staff->school_id)->first();
        
        // Check if attendance feature is active
        $attendance = Attendance::where('school_id', $school->id)->first();
        
        if (!$attendance || $attendance->status != 'ACTIVE') {
            return redirect()->back()->with('error', 'Attendance feature is not active for your school.');
        }

        // Get the class
        $class = SchClass::where('id', $classId)
            ->where('school_id', $school->id)
            ->firstOrFail();

        $today = Carbon::today();
        
        // Get all students in this class
        $students = Student::where('school_id', $school->id)
            ->where('class', $class->name)
            ->where('status', 'ACTIVE')
            ->get();
        
        // Get today's attendance records for this class
        $todayAttendances = StudentAttendance::where('school_id', $school->id)
            ->where('class_id', $classId)
            ->where('attendance_date', $today)
            ->get()
            ->keyBy('student_id');
        
        // Combine students with their attendance status
        $studentAttendances = collect([]);
        
        foreach($students as $student) {
            $attendanceRecord = $todayAttendances->get($student->id);
            $studentAttendances->push((object)[
                'student' => $student,
                'attendance' => $attendanceRecord,
                'marked' => $attendanceRecord ? true : false
            ]);
        }

        return view('staff.attendance.student.index', compact('studentAttendances', 'today', 'class'));
    }

    public function markAttendance(Request $request)
    {
        $staff = auth()->guard('staff')->user();
        $school = School::where('id', $staff->school_id)->first();
        
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:sch_classes,id',
            'status' => 'required|in:PRESENT,ABSENT,LATE,HALF_DAY',
            'check_in_time' => 'nullable|date_format:H:i',
            'remarks' => 'nullable|string|max:500',
        ]);

        $today = Carbon::today();
        
        // Check if student belongs to this school
        $student = Student::where('id', $request->student_id)
            ->where('school_id', $school->id)
            ->firstOrFail();

        // Create or update attendance
        $attendance = StudentAttendance::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'attendance_date' => $today,
            ],
            [
                'school_id' => $school->id,
                'class_id' => $request->class_id,
                'status' => $request->status,
                'check_in_time' => $request->check_in_time,
                'remarks' => $request->remarks,
                'marked_by' => $staff->id,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully',
            'attendance' => $attendance
        ]);
    }

    public function markAllPresent(Request $request)
    {
        $staff = auth()->guard('staff')->user();
        $school = School::where('id', $staff->school_id)->first();
        $today = Carbon::today();
        
        $request->validate([
            'class_id' => 'required|exists:sch_classes,id',
        ]);

        $class = SchClass::where('id', $request->class_id)
            ->where('school_id', $school->id)
            ->firstOrFail();
        
        $students = Student::where('school_id', $school->id)
            ->where('class', $class->name)
            ->where('status', 'ACTIVE')
            ->get();

        $marked = 0;
        foreach ($students as $student) {
            StudentAttendance::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'attendance_date' => $today,
                ],
                [
                    'school_id' => $school->id,
                    'class_id' => $request->class_id,
                    'status' => 'PRESENT',
                    'check_in_time' => Carbon::now()->format('H:i'),
                    'marked_by' => $staff->id,
                ]
            );
            $marked++;
        }

        return response()->json([
            'success' => true,
            'message' => "Marked {$marked} students as present"
        ]);
    }

    public function history(Request $request, $classId)
    {
        $staff = auth()->guard('staff')->user();
        $school = School::where('id', $staff->school_id)->first();
        
        $class = SchClass::where('id', $classId)
            ->where('school_id', $school->id)
            ->firstOrFail();
        
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        
        $attendances = StudentAttendance::where('school_id', $school->id)
            ->where('class_id', $classId)
            ->where('attendance_date', $selectedDate)
            ->with('student')
            ->get();

        $stats = [
            'total' => Student::where('school_id', $school->id)->where('class', $class->name)->where('status', 'ACTIVE')->count(),
            'present' => $attendances->where('status', 'PRESENT')->count(),
            'absent' => $attendances->where('status', 'ABSENT')->count(),
            'late' => $attendances->where('status', 'LATE')->count(),
            'half_day' => $attendances->where('status', 'HALF_DAY')->count(),
        ];

        return view('staff.attendance.student.history', compact('attendances', 'selectedDate', 'stats', 'class'));
    }

    public function report(Request $request, $classId)
    {
        $staff = auth()->guard('staff')->user();
        $school = School::where('id', $staff->school_id)->first();
        
        $class = SchClass::where('id', $classId)
            ->where('school_id', $school->id)
            ->firstOrFail();
        
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $students = Student::where('school_id', $school->id)
            ->where('class', $class->name)
            ->where('status', 'ACTIVE')
            ->get();

        $reportData = [];
        
        foreach ($students as $student) {
            $attendances = StudentAttendance::where('student_id', $student->id)
                ->whereBetween('attendance_date', [$startDate, $endDate])
                ->get();
            
            $reportData[] = [
                'student' => $student,
                'total_days' => $attendances->count(),
                'present' => $attendances->where('status', 'PRESENT')->count(),
                'absent' => $attendances->where('status', 'ABSENT')->count(),
                'late' => $attendances->where('status', 'LATE')->count(),
                'half_day' => $attendances->where('status', 'HALF_DAY')->count(),
            ];
        }

        return view('staff.attendance.student.report', compact('reportData', 'startDate', 'endDate', 'class'));
    }
}