<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (auth()->attempt($credentials)) {

        $user = auth()->user();

        if ($user->role === 'manager') {
            return redirect()->route('dashboard.sections');
        }

        elseif ($user->role === 'admin') {
            return  redirect()->route('admin.admindashboard');
        }

        if ($user->role === 'member' && $user->status === 'active') {

    return redirect()->route('activemember.posts.create')
        ->with('success', 'Welcome! ' . $user->name);

} elseif ($user->role === 'member' && $user->status === 'pending') {

    return redirect()->route('member.gallery')
        ->withErrors([
            'email' => 'Hello ' . $user->name . ', your account is pending approval.'
        ]);

} elseif ($user->status === 'blocked') {

    auth()->logout();

    return back()->with('error', 'You can’t use the platform anymore. Your account is blocked.');

}

        return redirect('/');
    }

    return back()->withErrors([
        'email' => 'Invalid credentials',
    ]);
}
}
