<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    
    private function adminUsers()
    {
        return User::where('role', 'admin')->orderBy('name')->get(['id', 'name', 'email']);
    }

   
    public function index()
    {
        $sections = Section::with('admin')->withCount('members')->latest()->get();
        $users    = $this->adminUsers();

        return view('manager.sections', compact('sections', 'users'));
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:80', 'unique:sections,name'],
            'tag'      => ['nullable', 'string', 'max:40'],
            'admin_id' => ['required', 'exists:users,id', 'in:' . $this->adminUsers()->pluck('id')->join(',')],
        ]);

        Section::create($validated);

        return redirect()
            ->route('dashboard.sections')
            ->with('success', 'Section "' . $validated['name'] . '" created successfully.');
    }

    
    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:80', 'unique:sections,name,' . $section->id],
            'tag'      => ['nullable', 'string', 'max:40'],
            'admin_id' => ['required', 'exists:users,id', 'in:' . $this->adminUsers()->pluck('id')->join(',')],
        ]);

        $section->update($validated);

        return redirect()
            ->route('dashboard.sections')
            ->with('success', 'Section updated successfully.');
    }

   
    public function destroy(Section $section)
    {
        $name = $section->name;
        $section->delete();

        return redirect()
            ->route('dashboard.sections')
            ->with('success', 'Section "' . $name . '" deleted.');
    }
}