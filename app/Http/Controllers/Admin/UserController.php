<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // List all users
    public function index()
    {
        $users = User::latest()->paginate(20); // paginate or get() as needed
        return view('admin.users', compact('users'));
    }

    // Show single user
    public function show($id)
    {
        $user = User::findOrFail($id);

        // Load all transactions for this user
        $transactions = $user->transactions()->latest()->get();
        
        return view('admin.view-user', compact('user', 'transactions'));
    }

    // Show create user form
    public function create()
    {
        return view('admin.users.create');
    }

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string|max:255',
            'email'=> 'required|email|unique:users,email',
            'class'=> 'nullable|string|max:50',
            'age'=> 'nullable|integer',
            'school'=> 'nullable|string|max:255',
            'department'=> 'nullable|string|max:255',
            'balance'=> 'nullable|numeric',
            'password'=> 'required|string|min:6',
        ]);

        User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'class'=> $request->class,
            'age'=> $request->age,
            'school'=> $request->school,
            'department'=> $request->department,
            'balance'=> $request->balance ?? 0,
            'password'=> bcrypt($request->password),
        ]);

        return redirect()->route('admin.users.index')
                         ->with('message', 'User created successfully.');
    }

    // Optional: update existing user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'=> 'required|string|max:255',
            'email'=> 'required|email|unique:users,email,' . $user->id,
            'class'=> 'nullable|string|max:50',
            'age'=> 'nullable|integer',
            'school'=> 'nullable|string|max:255',
            'department'=> 'nullable|string|max:255',
            'balance'=> 'nullable|numeric',
            'password'=> 'nullable|string|min:6',
        ]);

        $data = $request->only(['name','email','class','age','school','department','balance']);
        if($request->filled('password')){
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('message', 'User updated successfully.');
    }
}
