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
            'school_id'  => 'required|uuid|exists:schools,id',
            'class'      => 'required|string',
            'session'    => 'required|string',
            'term'       => 'required|string',

            'subjects'   => 'required|array',
            'subjects.*' => 'required|uuid',

            'test'       => 'nullable|array',
            'test.*'     => 'nullable|integer|min:0|max:40',

            'exam'       => 'nullable|array',
            'exam.*'     => 'nullable|integer|min:0|max:60',
        ]);

        // Find or create the result record
        $result = Result::firstOrCreate(
            [
                'student_id' => $validated['student_id'],
                'school_id'  => $validated['school_id'],
                'session'    => $validated['session'],
                'term'       => $validated['term'],
            ],
            [
                'class'  => $validated['class'],
                'scores' => json_encode([]),
            ]
        );

        // Load existing scores (important so other teachers' entries remain)
        $scores = json_decode($result->scores, true) ?? [];

        // Add/update subjects selected now
        foreach ($validated['subjects'] as $subjectId) {
            $scores[$subjectId] = [
                'test' => $validated['test'][$subjectId] ?? 0,
                'exam' => $validated['exam'][$subjectId] ?? 0,
            ];
        }

        // Save updated JSON
        $result->scores = json_encode($scores);
        $result->save();

        return back()->with('message', 'Results saved successfully.');
    }

public function showReportCard(Result $result)
{
    $staff = Auth::guard('staff')->user();
    $school = School::findOrFail($staff->school_id);
    $student = Student::findOrFail($result->student_id);

    /**
     * ------------------------------------------------
     * LOAD ALL SUBJECTS FOR THE SCHOOL
     * (We need subject names because we stored IDs)
     * ------------------------------------------------
     */
    $subjectMap = Subject::where('school_id', $school->id)
        ->pluck('name', 'id')
        ->toArray();

    /**
     * ------------------------------------------------
     * Determine Level (SSS, JSS, PRIMARY)
     * ------------------------------------------------
     */
    $level = ($student->class >= 10)
        ? 'SSS'
        : (($student->class >= 7) ? 'JSS' : 'PRIMARY');


    /**
     * ------------------------------------------------
     * Load All Terms for Cumulative Result
     * ------------------------------------------------
     */
    $firstTerm  = Result::where('student_id', $student->id)
        ->where('session', $result->session)
        ->where('term', 'First Term')
        ->first();

    $secondTerm = Result::where('student_id', $student->id)
        ->where('session', $result->session)
        ->where('term', 'Second Term')
        ->first();

    $thirdTerm  = Result::where('student_id', $student->id)
        ->where('session', $result->session)
        ->where('term', 'Third Term')
        ->first();


    /**
     * ------------------------------------------------
     * BUILD CURRENT TERM SCORES
     * FIX: Convert JSON string to array if needed
     * ------------------------------------------------
     */
    $currentScores = $result->scores ?? [];
    
    // CRITICAL FIX: Check if it's a JSON string
    if (is_string($currentScores)) {
        $currentScores = json_decode($currentScores, true);
    }
    
    // Ensure it's always an array
    if (!is_array($currentScores)) {
        $currentScores = [];
    }

    $processedCurrent = [];

    foreach ($currentScores as $subjectId => $score) {
        // Ensure $score is an array, convert if string
        if (is_string($score)) {
            $score = json_decode($score, true);
        }
        if (!is_array($score)) {
            $score = [];
        }

        $test  = $score['test'] ?? 0;
        $exam  = $score['exam'] ?? 0;
        $total = $test + $exam;

        $processedCurrent[$subjectId] = [
            'name'  => $subjectMap[$subjectId] ?? 'Unknown Subject',
            'test'  => $test,
            'exam'  => $exam,
            'total' => $total,
            'grade' => $this->gradeScore($total, $level),
        ];
    }


    /**
     * ------------------------------------------------
     * BUILD CUMULATIVE FOR THIRD TERM ONLY
     * ------------------------------------------------
     */
    $cumulative = [];

    if ($result->term === "Third Term") {
        foreach ($subjectMap as $subjectId => $name) {

            // Get scores from each term
            $t1Scores = $firstTerm ? ($firstTerm->scores ?? []) : [];
            $t2Scores = $secondTerm ? ($secondTerm->scores ?? []) : [];
            $t3Scores = $thirdTerm ? ($thirdTerm->scores ?? []) : [];

            // Ensure they are arrays
            if (is_string($t1Scores)) $t1Scores = json_decode($t1Scores, true) ?? [];
            if (is_string($t2Scores)) $t2Scores = json_decode($t2Scores, true) ?? [];
            if (is_string($t3Scores)) $t3Scores = json_decode($t3Scores, true) ?? [];

            $t1 = isset($t1Scores[$subjectId])
                ? ($t1Scores[$subjectId]['test'] + $t1Scores[$subjectId]['exam'])
                : null;

            $t2 = isset($t2Scores[$subjectId])
                ? ($t2Scores[$subjectId]['test'] + $t2Scores[$subjectId]['exam'])
                : null;

            $t3 = isset($t3Scores[$subjectId])
                ? ($t3Scores[$subjectId]['test'] + $t3Scores[$subjectId]['exam'])
                : null;

            // Avoid dividing null values
            $validScores = array_filter([$t1, $t2, $t3], fn($x) => $x !== null);

            $avg = count($validScores)
                ? array_sum($validScores) / count($validScores)
                : 0;

            $cumulative[$subjectId] = [
                'subject'  => $name,
                't1'       => $t1,
                't2'       => $t2,
                't3'       => $t3,
                'average'  => round($avg, 2),
                'grade'    => $this->gradeScore($avg, $level),
            ];
        }
    }


    /**
     * ------------------------------------------------
     * OVERALL AVERAGE & PRINCIPAL COMMENT
     * ------------------------------------------------
     */
    $totalScore = array_sum(array_column($processedCurrent, 'total'));
    $subjectCount = count($processedCurrent);
    $average = $subjectCount ? round($totalScore / $subjectCount, 2) : 0;

    $principalComment = $this->principalComment($average);


    /**
     * ------------------------------------------------
     * RETURN VIEW WITH ALL NECESSARY DATA
     * ------------------------------------------------
     */
    return view('staff.report-card', [
        'school' => $school,
        'student' => $student,

        'session' => $result->session,
        'term' => $result->term,

        'results' => $processedCurrent,
        'cumulative' => $cumulative,

        'totalScore' => $totalScore,
        'average' => $average,

        'subjectMap' => $subjectMap,
        'level' => $level,

        'principalComment' => $principalComment,
        'teachersComment' => $result->teachers_comment,

        'firstTerm' => $firstTerm,
        'secondTerm' => $secondTerm,
        'thirdTerm' => $thirdTerm,
    ]);
}

private function gradeScore($score, $level)
{
    $score = (float) $score;

    if ($level === 'SSS') {
        if ($score >= 80) return 'A1';
        if ($score >= 75) return 'B2';
        if ($score >= 70) return 'B3';
        if ($score >= 65) return 'C4';
        if ($score >= 60) return 'C5';
        if ($score >= 50) return 'C6';
        if ($score >= 45) return 'D7';
        if ($score >= 40) return 'E8';
        return 'F9';
    }

    // JSS / PRIMARY grading
    if ($score >= 70) return 'A';
    if ($score >= 60) return 'B';
    if ($score >= 50) return 'C';
    if ($score >= 45) return 'D';
    if ($score >= 40) return 'E';
    return 'F';
}

private function principalComment($average)
{
    if ($average >= 80) {
        return "Excellent performance. Keep up this outstanding standard.";
    } elseif ($average >= 70) {
        return "Very good performance. Continue to maintain this standard.";
    } elseif ($average >= 60) {
        return "Good performance. Strive for improvement in the next term.";
    } elseif ($average >= 50) {
        return "Fair performance. More effort is needed to improve.";
    } else {
        return "Poor performance. Urgent improvement required in the next term.";
    }
}

}
