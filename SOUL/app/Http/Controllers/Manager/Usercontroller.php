<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
   
    public function index(Request $request)
    {
        $search     = $request->get('search', '');
        $roleFilter = $request->get('role', '');   

        $users = User::query()
            ->where('role', '!=', 'manager')
            ->when($search, fn ($q) => $q
                ->where(fn ($inner) => $inner
                    ->where('name',  'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                )
            )
            ->when($roleFilter, fn ($q) => $q->where('role', $roleFilter))
            ->orderByRaw("FIELD(role, 'admin', 'member')")  
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $totalUsers = User::where('role', '!=', 'manager')->count();

        return view('manager.users', compact('users', 'totalUsers', 'search', 'roleFilter'));
    }

    
    public function promote(User $user)
    {
        abort_if($user->role === 'manager', 403);

        $user->update(['role' => 'admin']);

        return back()->with('success', "{$user->name} has been promoted to Admin.");
    }

    
    public function demote(User $user)
    {
        abort_if($user->role === 'manager', 403);

        if ($user->administeredSections()->exists()) {
            return back()->with('error', "{$user->name} is still assigned as admin of a section. Reassign it first.");
        }

        $user->update(['role' => 'user']);

        return back()->with('success', "{$user->name} has been demoted to Member.");
    }

    
    public function destroy(User $user)
    {
        abort_if($user->role === 'manager', 403);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "{$name} has been removed.");
    }
}