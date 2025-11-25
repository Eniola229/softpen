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
            'class' => 'required|string',
            'session' => 'required|string',
            'term' => 'required|string',
            'subjects' => 'required|array', // array of subject names
            'subjects.*' => 'required|string',
            'ca1' => 'nullable|array',
            'ca1.*' => 'nullable|integer|min:0|max:30',
            'ca2' => 'nullable|array',
            'ca2.*' => 'nullable|integer|min:0|max:30',
            'exam' => 'nullable|array',
            'exam.*' => 'nullable|integer|min:0|max:70',
        ]);

        // Find or create the result row for this student/session/term
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

        // Decode existing scores
        $scores = json_decode($result->scores, true) ?? [];

        // Loop through subjects and store their scores
        foreach ($validated['subjects'] as $subject) {
            $scores[$subject] = [
                'ca1' => $validated['ca1'][$subject] ?? 0,
                'ca2' => $validated['ca2'][$subject] ?? 0,
                'exam' => $validated['exam'][$subject] ?? 0,
            ];
        }

        // Save back to DB
        $result->scores = json_encode($scores);
        $result->save();

        return back()->with('message', 'Results updated successfully.');
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
