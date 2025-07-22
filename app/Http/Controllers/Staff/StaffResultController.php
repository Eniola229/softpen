<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

class StaffResultController extends Controller
{
    public function store(Request $request)
    {
        $staff = Auth::guard('staff')->user();

        $validated = $request->validate([
            'student_id' => 'required|uuid|exists:students,id',
            'school_id' => 'required|uuid|exists:schools,id',
            'subject_name' => 'required|string', // We are using subject name not ID
            'session' => 'required|string',
            'term' => 'required|string',
            'class' => 'required|string',
            'ca1' => 'nullable|integer|min:0|max:30',
            'ca2' => 'nullable|integer|min:0|max:30',
            'exam' => 'nullable|integer|min:0|max:70',
        ]);
        // Find or create result row
        $result = Result::firstOrCreate(
            [
                'student_id' => $validated['student_id'],
                'school_id' => $validated['school_id'],
                'session' => $validated['session'],
                'term' => $validated['term'],
            ],
            [
                'class' => $validated['class'],
                'scores' => json_encode([]),
            ]
        );

        // Decode scores from DB
        $scores = json_decode($result->scores, true) ?? [];

        // Add/Update subject scores
        $subject = $validated['subject_name'];
        $scores[$subject] = [
            'ca1' => $validated['ca1'] ?? 0,
            'ca2' => $validated['ca2'] ?? 0,
            'exam' => $validated['exam'] ?? 0,
        ];

        // Re-encode and save
        $result->scores = json_encode($scores);
        $result->save();


        return back()->with('success', 'Result updated successfully.');
    }


    public function showReportCard(Result $result)
    {
        $staff = Auth::guard('staff')->user();
        $school = School::findOrFail($staff->school_id);

        // Get all results for that student, session, and term
        $results = Result::with('subject')
            ->where('student_id', $result->student_id)
            ->where('school_id', $school->id)
            ->where('session', $result->session)
            ->where('term', $result->term)
            ->get();

        $student = Student::findOrFail($result->student_id);

        $totalScore = $results->sum(fn($r) => ($r->ca1 ?? 0) + ($r->ca2 ?? 0) + ($r->exam ?? 0));
        $subjectCount = $results->count();
        $average = $subjectCount > 0 ? round($totalScore / $subjectCount, 2) : 0;

        return view('staff.report-card', [
            'student' => $student,
            'results' => $results,
            'session' => $result->session,
            'term' => $result->term,
            'totalScore' => $totalScore,
            'average' => $average,
        ]);
    }

}
