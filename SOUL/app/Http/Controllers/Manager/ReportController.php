<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\AdminReport;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $adminReports     = AdminReport::with('admin')->latest()->get();
        $memberComplaints = collect();

        $activeCount   = AdminReport::where('status', 'pending')->count();
        $criticalCount = AdminReport::where('category', 'security')->count();

        return view('manager.reports', compact(
            'adminReports',
            'memberComplaints',
            'activeCount',
            'criticalCount'
        ));
    }

    public function show(AdminReport $report)
    {
        $report->load('admin');
        return view('manager.report-show', compact('report'));
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
        $report->delete();
        return back()->with('success', 'Report deleted successfully.');
    }
}