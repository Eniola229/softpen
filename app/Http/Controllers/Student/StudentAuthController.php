<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\School;
use Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Waitlist;
use App\Models\SchClass;
use App\Models\Department;
use App\Models\Subject;
use App\Models\Result;
class StudentAuthController extends Controller
{
    /**
     * Show the login page.
     *
     * @return View
     */
    public function index(): View
    {
        return view('student.login');
    }  

    /**
     * Show the registration page.
     *
     * @return View
     */
    public function registration(): View
    {
        return view('student.registration');
    }

    /**
     * Handle login request.
     *
     * @param Request $request
     * @return RedirectResponse
     */
        public function postLogin(Request $request): RedirectResponse
        {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');

            // Use the 'school' guard for authentication
            if (Auth::guard('student')->attempt($credentials)) {
                // Get the authenticated student
                $student = Auth::guard('student')->user();

                // Check if the student status is 'DISACTIVATE'
                if ($student->status == "DISACTIVATE") {
                    Auth::guard('student')->logout(); // Log the user out if the account is deactivated
                    return redirect("student/login")->with('error', 'Oops! This student has been set to DISACTIVATED. Kindly message your school admin.');
                }

                return redirect('student/dashboard')->withSuccess('You have successfully logged in');
            }

            return redirect("student/login")->with('error', 'Oops! You have entered invalid credentials');
        }

    // public function dashboard()
    // {
    //     // $schools = School::orderBy('created_at', 'desc')->get();
    //     $student = Auth::guard('student')->user();

    //     if (Auth::guard('student')->check()) {
    //         return view('student.dashboard');
    //     }

    //     return redirect("student/login")->withSuccess('Oops! You do not have access');
    // }

    public function Result()
    {
        $student = Auth::guard('student')->user();
        $school = School::findOrFail($student->school_id);

        // Fetch results
        $results = Result::where('student_id', $student->id)
            ->where('school_id', $school->id)
            ->get()
            ->groupBy(['session', 'term']);

        return view('student.result', compact('student', 'results'));
    }


    /**
     * Handle admin logout.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Session::flush();
        Auth::guard('student')->logout();

        return redirect('student/login');
    }
}
