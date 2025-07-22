<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Staff;
use App\Models\Student;
use App\Models\SchClass;
use App\Models\Department;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use App\Models\Result;

class ResultController extends Controller
{
        public function index()
    {
        $student = Auth::user();
        $school = School::findOrFail($student->school_id);

        // Fetch all results for this student grouped by session and term
        $results = Result::with('subject')
            ->where('student_id', $student->id)
            ->where('school_id', $school->id)
            ->get()
            ->groupBy(['session', 'term']);


        return view('view-result', compact('results'));
    }

    public function showMyReportCard(Result $result)
    {
        $student = Auth::user();

        // Authorization check to ensure result belongs to the logged-in student
        if ($result->student_id !== $student->id) {
            abort(403, 'Unauthorized access to report card.');
        }

        $school = School::findOrFail($student->school_id);

        $results = Result::with('subject')
            ->where('student_id', $student->id)
            ->where('school_id', $school->id)
            ->where('session', $result->session)
            ->where('term', $result->term)
            ->get();

        $totalScore = $results->sum(fn($r) => ($r->ca1 ?? 0) + ($r->ca2 ?? 0) + ($r->exam ?? 0));
        $subjectCount = $results->count();
        $average = $subjectCount > 0 ? round($totalScore / $subjectCount, 2) : 0;

        return view('report-card', [
            'student' => $student,
            'results' => $results,
            'session' => $result->session,
            'term' => $result->term,
            'totalScore' => $totalScore,
            'average' => $average,
        ]);
    }

}
