<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostReport;
use App\Models\Section;
use Illuminate\Http\Request;

class PostReportController extends Controller
{
   
    private function adminSection(): Section
    {
        $section = auth()->user()->administeredSections()->first();
        abort_if(!$section, 403, 'You are not assigned as admin of any section.');
        return $section;
    }

    
    public function index(Request $request)
    {
        $section       = $this->adminSection();
        $reasonFilter  = $request->get('reason', '');

        $reports = PostReport::with(['post.user', 'reporter'])
            ->inSection($section->id)
            ->when($reasonFilter, fn ($q) => $q->where('reason', $reasonFilter))
            ->latest()
            ->paginate(12)
            ->withQueryString();

       
        $counts = [
            'all'               => PostReport::inSection($section->id)->count(),
            'spam'              => PostReport::inSection($section->id)->where('reason', 'spam')->count(),
            'harassment'        => PostReport::inSection($section->id)->where('reason', 'harassment')->count(),
            'inappropriate'     => PostReport::inSection($section->id)->where('reason', 'inappropriate')->count(),
            'false_information' => PostReport::inSection($section->id)->where('reason', 'false_information')->count(),
        ];

        return view('admin.Reportedposts', compact(
            'reports', 'section', 'reasonFilter', 'counts'
        ));
    }

    
    public function keep(PostReport $report)
    {
        $this->authorizeReport($report);

        $report->update([
            'status'      => 'kept',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Post has been reviewed and kept.');
    }

    
    public function deletePost(PostReport $report)
    {
        $this->authorizeReport($report);

        
        if ($report->post) {
            $report->post->update([
                'deleted_by'          => auth()->id(),
                'deleted_at_by_admin' => now(),
                'status'              => 'rejected',
            ]);
            $report->post->delete();
        }

        $report->update([
            'status'      => 'deleted',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Post has been deleted.');
    }

    
    private function authorizeReport(PostReport $report): void
    {
        $section = $this->adminSection();
        abort_if(
            !$report->post || $report->post->section_id !== $section->id,
            403,
            'You cannot moderate reports outside your section.'
        );
    }
}