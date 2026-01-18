<?php

namespace App\Http\Controllers\School;

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
use App\Models\Result;
use App\Models\CBT;
use App\Models\Attendance;

class SchoolAuthController extends Controller
{
    /**
     * Show the login page.
     *
     * @return View
     */
    public function index(): View
    {
        return view('school.login');
    }  

    /**
     * Show the registration page.
     *
     * @return View
     */
    public function registration(): View
    {
        return view('school.registration');
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
            if (Auth::guard('school')->attempt($credentials)) {
                // Get the authenticated school
                $school = Auth::guard('school')->user();

                // Check if the school status is 'DISACTIVATE'
                if ($school->status == "DISACTIVATE") {
                    Auth::guard('school')->logout(); // Log the user out if the account is deactivated
                    return redirect("school/login")->with('error', 'Oops! This school has been set to DISACTIVATED. Kindly message admin.');
                }

                return redirect('school/dashboard')->withSuccess('You have successfully logged in');
            }

            return redirect("school/login")->with('error', 'Oops! You have entered invalid credentials');
        }

    public function dashboard()
    {
        // $schools = School::orderBy('created_at', 'desc')->get();
        $school = Auth::guard('school')->user();
        $staffCount = Staff::where('school_id', $school->id)->get()->count();
        $studentCount = Student::where('school_id', $school->id)->get()->count();
        $resultCount = Result::where('school_id', $school->id)->get()->count();
        $cbt = CBT::where('school_id', $school->id)->first();
        $attendance = Attendance::where('school_id', $school->id)->first();
    
        if (Auth::guard('school')->check()) {
            return view('school.dashboard', compact("staffCount", "studentCount", "resultCount", "cbt", "attendance"));
        }

        return redirect("school/login")->withSuccess('Oops! You do not have access');
    }



    /**
     * Handle admin logout.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Session::flush();
        Auth::guard('school')->logout();

        return redirect('school/login');
    }
}
