<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StaffAttendanceController extends Controller
{
public function index()
{
    $school = auth()->guard('school')->user();
    
    // Check if attendance feature is active
    $attendance = Attendance::where('school_id', $school->id)->first();
    
    if (!$attendance || $attendance->status != 'ACTIVE') {
        return redirect()->route('school-dashboard')->with('error', 'Attendance feature is not active for your school.');
    }

    $today = Carbon::today();
    
    // Get all active staff
    $staffs = Staff::where('school_id', $school->id)
        ->where('status', 'ACTIVE')
        ->get();
    
    // Get today's attendance records
    $todayAttendances = StaffAttendance::where('school_id', $school->id)
        ->where('attendance_date', $today)
        ->get()
        ->keyBy('staff_id');
    
    // Combine staff with their attendance status
    $staffAttendances = collect([]);
    
    foreach($staffs as $staff) {
        $attendance = $todayAttendances->get($staff->id);
        $staffAttendances->push((object)[
            'staff' => $staff,
            'attendance' => $attendance,
            'marked' => $attendance ? true : false
        ]);
    }

    return view('school.attendance.staff.index', compact('staffAttendances', 'today'));
}

    public function markAttendance(Request $request)
    {
        $school = auth()->guard('school')->user();
        
        $request->validate([
            'staff_id' => 'required|exists:staffs,id',
            'status' => 'required|in:PRESENT,ABSENT,LATE,HALF_DAY',
            'check_in_time' => 'nullable|date_format:H:i',
            'remarks' => 'nullable|string|max:500',
        ]);

        $today = Carbon::today();
        
        // Check if staff belongs to this school
        $staff = Staff::where('id', $request->staff_id)
            ->where('school_id', $school->id)
            ->firstOrFail();

        // Create or update attendance
        $attendance = StaffAttendance::updateOrCreate(
            [
                'staff_id' => $request->staff_id,
                'attendance_date' => $today,
            ],
            [
                'school_id' => $school->id,
                'status' => $request->status,
                'check_in_time' => $request->check_in_time,
                'remarks' => $request->remarks,
                'marked_by' => $school->id,
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
        $school = auth()->guard('school')->user();
        $today = Carbon::today();
        
        $staffs = Staff::where('school_id', $school->id)
            ->where('status', 'ACTIVE')
            ->get();

        $marked = 0;
        foreach ($staffs as $staff) {
            StaffAttendance::updateOrCreate(
                [
                    'staff_id' => $staff->id,
                    'attendance_date' => $today,
                ],
                [
                    'school_id' => $school->id,
                    'status' => 'PRESENT',
                    'check_in_time' => Carbon::now()->format('H:i'),
                    'marked_by' => $school->id,
                ]
            );
            $marked++;
        }

        return response()->json([
            'success' => true,
            'message' => "Marked {$marked} staff as present"
        ]);
    }

    public function history(Request $request)
    {
        $school = auth()->guard('school')->user();
        
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        
        $attendances = StaffAttendance::where('school_id', $school->id)
            ->where('attendance_date', $selectedDate)
            ->with('staff')
            ->get();

        $stats = [
            'total' => Staff::where('school_id', $school->id)->where('status', 'ACTIVE')->count(),
            'present' => $attendances->where('status', 'PRESENT')->count(),
            'absent' => $attendances->where('status', 'ABSENT')->count(),
            'late' => $attendances->where('status', 'LATE')->count(),
            'half_day' => $attendances->where('status', 'HALF_DAY')->count(),
        ];

        return view('school.attendance.staff.history', compact('attendances', 'selectedDate', 'stats'));
    }

    public function report(Request $request)
    {
        $school = auth()->guard('school')->user();
        
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $staffs = Staff::where('school_id', $school->id)
            ->where('status', 'ACTIVE')
            ->get();

        $reportData = [];
        
        foreach ($staffs as $staff) {
            $attendances = StaffAttendance::where('staff_id', $staff->id)
                ->whereBetween('attendance_date', [$startDate, $endDate])
                ->get();
            
            $reportData[] = [
                'staff' => $staff,
                'total_days' => $attendances->count(),
                'present' => $attendances->where('status', 'PRESENT')->count(),
                'absent' => $attendances->where('status', 'ABSENT')->count(),
                'late' => $attendances->where('status', 'LATE')->count(),
                'half_day' => $attendances->where('status', 'HALF_DAY')->count(),
            ];
        }

        return view('school.attendance.staff.report', compact('reportData', 'startDate', 'endDate'));
    }
}