<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // 👇 تحقق واش هذا أول user
        $isFirstUser = User::count() === 0;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $isFirstUser ? 'manager' : 'member',
            'status' => $isFirstUser ? 'active' : 'pending',
        ]);
        Auth::login($user);

        if ($user->role === 'manager') {
        return redirect()->route('dashboard.sections');
    } else {
        return redirect()->route('member.gallery');
    }

      
    }
}

