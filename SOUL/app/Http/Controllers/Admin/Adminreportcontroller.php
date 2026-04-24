<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $activeCount   = AdminReport::where('status', 'pending')->count();
        $criticalCount = AdminReport::where('category', 'security')->count();

        if ($user->role === 'admin') {
            $adminReports     = AdminReport::with('admin')
                                    ->where('admin_id', $user->id)
                                    ->latest()
                                    ->get();
            $memberComplaints = collect();

            return view('admin.reports', compact(
                'adminReports',
                'memberComplaints',
                'activeCount',
                'criticalCount'
            ));
        }

        if ($user->role === 'manager') {
            $adminReports     = AdminReport::with('admin')->latest()->get();
            $memberComplaints = collect();

            return view('manager.reports', compact(
                'adminReports',
                'memberComplaints',
                'activeCount',
                'criticalCount'
            ));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category'    => ['required', 'in:security,user_conduct,technical,other'],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'attachment'  => ['nullable', 'file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,mp4,mov,doc,docx'],
        ]);

        $attachmentPath     = null;
        $attachmentFilename = null;
        $attachmentMime     = null;

        if ($request->hasFile('attachment')) {
            $file               = $request->file('attachment');
            $attachmentPath     = $file->store('reports/attachments', 'public');
            $attachmentFilename = $file->getClientOriginalName();
            $attachmentMime     = $file->getMimeType();
        }

        AdminReport::create([
            'admin_id'            => auth()->id(),
            'category'            => $validated['category'],
            'description'         => $validated['description'],
            'attachment_path'     => $attachmentPath,
            'attachment_filename' => $attachmentFilename,
            'attachment_mime'     => $attachmentMime,
            'status'              => 'pending',
        ]);

        return redirect()->route('admin.reports')->with('success', 'Report submitted successfully.');
    }

    public function resolve(AdminReport $report)
    {
        $report->update([
            'status'      => 'reviewed',
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Complaint resolved successfully.');
    }

    public function review(Request $request, AdminReport $report)
    {
        $request->validate([
            'status'        => ['required', 'in:reviewed,dismissed'],
            'manager_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $report->update([
            'status'        => $request->status,
            'manager_notes' => $request->manager_notes,
            'reviewed_at'   => now(),
        ]);

        return back()->with('success', 'Report updated successfully.');
    }

    public function destroy(AdminReport $report)
    {
        if ($report->attachment_path) {
            Storage::disk('public')->delete($report->attachment_path);
        }

        $report->delete();

        return back()->with('success', 'Report deleted successfully.');
    }
}