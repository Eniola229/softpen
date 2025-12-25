<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'class' => ['required', 'string', 'max:255'],
            'age' => ['required', 'string', 'max:255'],
            'school' => ['required', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'class' => $request->class,
            'age' => $request->age,
            'school' => $request->school,
            'department' => $request->department,
            'balance' => 500.00, // Set initial bonus
            'state' => $request->state,
            'country' => $request->country,
            'password' => Hash::make($request->password),
        ]);

        // Create welcome bonus transaction
        Transaction::create([
            'user_id' => $user->id,
            'type' => Transaction::TYPE_CREDIT,
            'category' => Transaction::CATEGORY_WALLET_TOPUP,
            'amount' => 500.00,
            'balance_before' => 0.00,
            'balance_after' => 500.00,
            'reference' => Transaction::generateReference(),
            'status' => Transaction::STATUS_COMPLETED,
            'payment_method' => 'bonus',
            'description' => 'Welcome bonus for new registration',
            'metadata' => ['bonus_type' => 'registration'],
        ]);

        // Send welcome email
        $this->sendWelcomeEmail($user);

        // Send bonus notification email
        $this->sendBonusEmail($user);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard'))->with('success', 'Welcome! You have received a bonus of ₦500.00 in your wallet!');
    }

    /**
     * Send welcome email to new user
     */
    private function sendWelcomeEmail(User $user): void
    {
        try {
            Mail::send('emails.welcome', ['user' => $user], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('Welcome to SchoolCode Africa');
            });
        } catch (\Exception $e) {
            \Log::error('Welcome email failed: ' . $e->getMessage());
        }
    }

    /**
     * Send bonus notification email
     */
    private function sendBonusEmail(User $user): void
    {
        try {
            Mail::send('emails.bonus', ['user' => $user], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('Congratulations! You\'ve Received ₦500 Bonus');
            });
        } catch (\Exception $e) {
            \Log::error('Bonus email failed: ' . $e->getMessage());
        }
    }
}