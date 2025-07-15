<?php

namespace App\Http\Controllers\Staff;

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

class StaffAuthController extends Controller
{
    /**
     * Show the login page.
     *
     * @return View
     */
    public function index(): View
    {
        return view('staff.login');
    }  

    /**
     * Show the registration page.
     *
     * @return View
     */
    public function registration(): View
    {
        return view('staff.registration');
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
            if (Auth::guard('staff')->attempt($credentials)) {
                // Get the authenticated staff
                $staff = Auth::guard('staff')->user();

                // Check if the staff status is 'DISACTIVATE'
                if ($staff->status == "DISACTIVATE") {
                    Auth::guard('staff')->logout(); // Log the user out if the account is deactivated
                    return redirect("staff/login")->with('error', 'Oops! This staff has been set to DISACTIVATED. Kindly message admin.');
                }

                return redirect('staff/dashboard')->withSuccess('You have successfully logged in');
            }

            return redirect("staff/login")->with('error', 'Oops! You have entered invalid credentials');
        }

    public function dashboard()
    {
        // $schools = School::orderBy('created_at', 'desc')->get();
        $staff = Auth::guard('staff')->user();

        if (Auth::guard('staff')->check()) {
            return view('staff.dashboard');
        }

        return redirect("staff/login")->withSuccess('Oops! You do not have access');
    }



    /**
     * Handle admin logout.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Session::flush();
        Auth::guard('staff')->logout();

        return redirect('staff/login');
    }
}
