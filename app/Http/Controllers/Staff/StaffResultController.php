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
        // 'teachers_comment' => 'nullable|string|max:500',
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
            // 'teachers_comment'  => $validated['teachers_comment'],
            'class'  => $validated['class'],
            'scores' => json_encode([]),
        ]
    );

    // Load existing scores
    $scores = is_string($result->scores) 
        ? (json_decode($result->scores, true) ?? []) 
        : ($result->scores ?? []);

    // Add/update subjects with their test and exam scores
    foreach ($validated['subjects'] as $subjectId) {
        $scores[$subjectId] = [
            'test' => (int)($validated['test'][$subjectId] ?? 0),
            'exam' => (int)($validated['exam'][$subjectId] ?? 0),
        ];
    }

    // Debug: See what's being saved
    \Log::info('Scores being saved:', $scores);

    // Update the result with merged scores and teachers comment
    $result->update([
        'scores' => json_encode($scores),
        // 'teachers_comment' => $validated['teachers_comment'] ?? $result->teachers_comment,
        'class' => $validated['class'] ?? $result->class,
    ]);

    return back()->with('message', 'Results saved successfully.');
}
public function showReportCard(Result $result)
{
    $student = Student::findOrFail($result->student_id);
    $school = School::findOrFail($student->school_id);

    /**
     * ------------------------------------------------
     * LOAD ALL SUBJECTS FOR THE SCHOOL
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
     * ------------------------------------------------
     */
    $currentScores = $result->scores ?? [];
    
    // Convert JSON string to array if needed
    if (is_string($currentScores)) {
        $currentScores = json_decode($currentScores, true) ?? [];
    }
    
    if (!is_array($currentScores)) {
        $currentScores = [];
    }

    $processedCurrent = [];

    foreach ($currentScores as $subjectId => $score) {
        // Ensure $score is an array
        if (is_string($score)) {
            $score = json_decode($score, true) ?? [];
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

            // Get scores from each term and decode if string
            $t1Scores = $firstTerm ? ($firstTerm->scores ?? []) : [];
            $t2Scores = $secondTerm ? ($secondTerm->scores ?? []) : [];
            $t3Scores = $thirdTerm ? ($thirdTerm->scores ?? []) : [];

            // Decode JSON strings
            if (is_string($t1Scores)) $t1Scores = json_decode($t1Scores, true) ?? [];
            if (is_string($t2Scores)) $t2Scores = json_decode($t2Scores, true) ?? [];
            if (is_string($t3Scores)) $t3Scores = json_decode($t3Scores, true) ?? [];

            // Calculate term totals
            $t1 = isset($t1Scores[$subjectId])
                ? ($t1Scores[$subjectId]['test'] ?? 0) + ($t1Scores[$subjectId]['exam'] ?? 0)
                : null;

            $t2 = isset($t2Scores[$subjectId])
                ? ($t2Scores[$subjectId]['test'] ?? 0) + ($t2Scores[$subjectId]['exam'] ?? 0)
                : null;

            $t3 = isset($t3Scores[$subjectId])
                ? ($t3Scores[$subjectId]['test'] ?? 0) + ($t3Scores[$subjectId]['exam'] ?? 0)
                : null;

            // Calculate average
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
     * CALCULATE OVERALL AVERAGES
     * ------------------------------------------------
     */
    $totalScore = array_sum(array_column($processedCurrent, 'total'));
    $subjectCount = count($processedCurrent);
    $average = $subjectCount ? round($totalScore / $subjectCount, 2) : 0;

    $principalComment = $this->principalComment($average, $result->term);

    /**
     * ------------------------------------------------
     * RETURN VIEW WITH ALL DATA
     * ------------------------------------------------
     */
    return view('staff.report-card', [
        'school' => $school,
        'student' => $student,
        'session' => $result->session,
        'class' => $result->class,
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

private function principalComment($average, $term = null)
{
    if ($term === 'Third Term') {
        if ($average >= 70) {
            return "Excelent performance, promoted.";
        } elseif ($average >= 50 && $average < 70) {
            return "Good performance put in more effort, promoted.";
        } elseif ($average >= 40 && $average < 50) {
            return "Average performance, promoted.";
        } elseif ($average >= 1 && $average < 40) {
            return "Under performance, advice to repeat.";
        } else {
            return "";
        }
    }

    // For 1st and 2nd Term
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

    public function updateComment(Request $request, $studentId, $term, $session)
    {
        $request->validate([
            'teacher_comment' => 'required|string|max:1000',
        ]);

        // Update all results for this student, term, and session with the same comment
        $updated = Result::where('student_id', $studentId)
            ->where('term', $term)
            ->where('session', $session)
            ->update([
                'teachers_comment' => $request->teacher_comment,
                'updated_at' => now(),
            ]);

        if ($updated) {
            return redirect()->back()->with('success', 'Comment updated successfully.');
        }

        return redirect()->back()->with('error', 'No results found to update.');
    }
}
