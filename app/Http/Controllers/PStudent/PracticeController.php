<?php
namespace App\Http\Controllers\PStudent;

use App\Http\Controllers\Controller;
use App\Models\PClass;
use App\Models\PExam;
use App\Models\PQuestion;
use App\Models\PStudentAttempt;
use App\Models\PStudentAnswer;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PracticeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $classes = PClass::all();

        return view('practice.index', compact('classes', 'user'));
    }

    public function showExams($classId)
    {
        $user = Auth::user();
        $class = PClass::findOrFail($classId);
        $exams = PExam::where('p_class_id', $classId)
            ->where('is_published', true)
            ->get();

        return view('practice.exams', compact('class', 'exams', 'user'));
    }

    public function showInstructions($classId, $examId)
    {
        $user = Auth::user();
        $class = PClass::findOrFail($classId);
        $exam = PExam::findOrFail($examId);

        // Check if user has enough balance (N100)
        if ($user->balance < 100) {
            return redirect()->back()->with('error', 'Insufficient balance. You need at least ₦100 to take a practice exam.');
        }

        return view('practice.instructions', compact('class', 'exam', 'user'));
    }

    public function startExam(Request $request, $classId, $examId)
    {
        $user = Auth::user();
        $exam = PExam::findOrFail($examId);

        // Check balance
        if ($user->balance < 100) {
            return redirect()->back()->with('error', 'Insufficient balance. You need at least ₦100 to take a practice exam.');
        }

        // Deduct N20 from user balance
        $balanceBefore = $user->balance; // Capture balance before transaction
        $deductionAmount = 20;

        $user->decrement('balance', $deductionAmount);

        Transaction::create([
            'user_id' => $user->id,
            'type' => Transaction::TYPE_DEBIT,
            'category' => Transaction::CATEGORY_COURSE_PURCHASE,
            'amount' => 20.00,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceBefore - $deductionAmount,
            'reference' => Transaction::generateReference(),
            'status' => Transaction::STATUS_COMPLETED,
            'payment_method' => 'wallet', // or 'balance' depending on your system
            'description' => 'Exam Pratice Purchase Deduction',
            'metadata' => [
                'course_id' => $courseId ?? null, // Add course reference if available
                'transaction_type' => 'course_purchase'
            ],
        ]);

        // Create attempt
        $attempt = PStudentAttempt::create([
            'student_id' => $user->id,
            'p_exam_id' => $examId,
            'started_at' => Carbon::now(),
            'total_marks' => $exam->questions->sum('mark'),
        ]);

        return redirect()->route('practice.take', [$classId, $examId, $attempt->id]);
    }

    public function takeExam($classId, $examId, $attemptId)
    {
        $user = Auth::user();
        $class = PClass::findOrFail($classId);
        $exam = PExam::with('questions.options')->findOrFail($examId);
        $attempt = PStudentAttempt::findOrFail($attemptId);

        // Check if attempt belongs to user
        if ($attempt->student_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        // Check if already completed
        if ($attempt->is_completed) {
            return redirect()->route('practice.result', [$classId, $examId, $attempt->id])
                ->with('error', 'This exam has already been completed.');
        }

        // Get questions
        $questions = $exam->questions()->orderBy('order')->get();
        
        if ($exam->randomize_questions) {
            $questions = $questions->shuffle();
        }

        return view('practice.take', compact('class', 'exam', 'attempt', 'questions', 'user'));
    }

    public function submitExam(Request $request, $classId, $examId, $attemptId)
    {
        $user = Auth::user();
        $exam = PExam::with('questions.options')->findOrFail($examId);
        $attempt = PStudentAttempt::findOrFail($attemptId);

        // Check if attempt belongs to user
        if ($attempt->student_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        // Check if already completed
        if ($attempt->is_completed) {
            return redirect()->route('practice.result', [$classId, $examId, $attempt->id])
                ->with('error', 'This exam has already been submitted.');
        }

        $totalScore = 0;
        $totalMarks = $exam->questions->sum('mark');

        // Process answers
        foreach ($exam->questions as $question) {
            $answerId = $request->input("question_{$question->id}");
            $answerText = $request->input("question_{$question->id}_text");
            
            $isCorrect = false;
            $marksObtained = 0;

            if ($question->question_type === 'multiple_choice' || $question->question_type === 'true_false') {
                $selectedOption = $question->options()->find($answerId);
                
                if ($selectedOption && $selectedOption->is_correct) {
                    $isCorrect = true;
                    $marksObtained = $question->mark;
                    $totalScore += $marksObtained;
                }

                PStudentAnswer::create([
                    'p_student_attempt_id' => $attempt->id,
                    'p_question_id' => $question->id,
                    'p_question_option_id' => $answerId,
                    'is_correct' => $isCorrect,
                    'marks_obtained' => $marksObtained,
                ]);
            } elseif ($question->question_type === 'short_answer') {
                PStudentAnswer::create([
                    'p_student_attempt_id' => $attempt->id,
                    'p_question_id' => $question->id,
                    'answer_text' => $answerText,
                    'is_correct' => false,
                    'marks_obtained' => 0,
                ]);
            }
        }

        // Calculate percentage
        $percentage = $totalMarks > 0 ? ($totalScore / $totalMarks) * 100 : 0;
        $passed = $percentage >= $exam->passing_score;

        // Update attempt
        $attempt->update([
            'score' => $totalScore,
            'total_marks' => $totalMarks,
            'percentage' => $percentage,
            'passed' => $passed,
            'completed_at' => Carbon::now(),
            'time_spent' => Carbon::parse($attempt->started_at)->diffInSeconds(Carbon::now()),
            'is_completed' => true,
        ]);

        return redirect()->route('practice.result', [$classId, $examId, $attempt->id]);
    }

    public function showResult($classId, $examId, $attemptId)
    {
        $user = Auth::user();
        $class = PClass::findOrFail($classId);
        $exam = PExam::findOrFail($examId);
        $attempt = PStudentAttempt::with('answers.pQuestion.options', 'answers.selectedOption')->findOrFail($attemptId);

        // Check if attempt belongs to user
        if ($attempt->student_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        return view('practice.result', compact('class', 'exam', 'attempt', 'user'));
    }

    public function myAttempts()
    {
        $user = Auth::user();
        $attempts = PStudentAttempt::with('pExam')
            ->where('student_id', $user->id)
            ->where('is_completed', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('practice.attempts', compact('attempts', 'user'));
    }

    public function transactionHistory()
    {
        $user = Auth::user();
        
        // Get transactions with filtering options
        $query = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');
        
        // Apply filters if present in request
        if (request()->has('type') && request('type') !== 'all') {
            $query->where('type', request('type'));
        }
        
        if (request()->has('category') && request('category') !== 'all') {
            $query->where('category', request('category'));
        }
        
        if (request()->has('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }
        
        // Date range filter
        if (request()->has('date_from') && request('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }
        
        if (request()->has('date_to') && request('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }
        
        $transactions = $query->paginate(15);
        
        // Calculate statistics
        $stats = [
            'total_credit' => Transaction::where('user_id', $user->id)
                ->where('type', Transaction::TYPE_CREDIT)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->sum('amount'),
            'total_debit' => Transaction::where('user_id', $user->id)
                ->where('type', Transaction::TYPE_DEBIT)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->sum('amount'),
            'pending_count' => Transaction::where('user_id', $user->id)
                ->where('status', Transaction::STATUS_PENDING)
                ->count(),
            'completed_count' => Transaction::where('user_id', $user->id)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->count(),
        ];
        
        return view('transactions.history', compact('transactions', 'user', 'stats'));
    }
}