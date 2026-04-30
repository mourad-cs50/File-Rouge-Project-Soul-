<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class Dashboardcontroller extends Controller
{
    public function index(Request $request)
    {
        $search       = $request->get('search', '');
        $statusFilter = $request->get('status', '');

        $adminSectionId = auth()->user()->section_id;

      
        $totalMembers = User::where('role', 'member')
            ->where('section_id', $adminSectionId)
            ->count();

        $activeMembers = User::where('role', 'member')
            ->where('section_id', $adminSectionId)
            ->where('status', 'active')
            ->count();

        $sectionHealth = $totalMembers > 0
            ? round(($activeMembers / $totalMembers) * 100, 1)
            : 0;

        
        $members = User::query()
            ->where('role', 'member') 
            ->where('section_id', $adminSectionId) 
            ->when($search, fn ($q) => $q
                ->where(fn ($inner) => $inner
                    ->where('name',  'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                )
            )
            ->when($statusFilter, fn ($q) => $q->where('status', $statusFilter))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.dashboard', compact(
            'members',
            'totalMembers',
            'activeMembers',
            'sectionHealth',
            'search',
            'statusFilter'
        ));
    }

    public function ban(User $user)
    {
        abort_if($user->isManager(), 403);

        
        abort_if($user->section_id !== auth()->user()->section_id, 403);

      
        abort_if($user->role !== 'member', 403);

        $user->update(['status' => 'banned']);

        return back()->with('success', "{$user->name} has been banned.");
    }

    public function activate(User $user)
    {
        abort_if($user->isManager(), 403);

        abort_if($user->section_id !== auth()->user()->section_id, 403);
        abort_if($user->role !== 'member', 403);

        $user->update(['status' => 'active']);

        return back()->with('success', "{$user->name} has been restored to Active.");
    }

    public function approve(User $user)
    {
        abort_if($user->isManager(), 403);

        abort_if($user->section_id !== auth()->user()->section_id, 403);
        abort_if($user->role !== 'member', 403);

        abort_if($user->status !== 'pending', 422);

        $user->update(['status' => 'active']);

        return back()->with('success', "{$user->name} has been approved.");
    }
}