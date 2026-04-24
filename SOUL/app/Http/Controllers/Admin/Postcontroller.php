<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Resolve the section the authenticated admin is responsible for.
     * Aborts with 403 if they have no section.
     */
    private function adminSection(): Section
    {
        $section = auth()->user()->administeredSections()->first();

        abort_if(!$section, 403, 'You are not assigned as admin of any section.');

        return $section;
    }

    /**
     * Ensure the given post belongs to the admin's section.
     * Aborts with 403 if it doesn't.
     */
    private function authorizePost(Post $post): void
    {
        $section = $this->adminSection();

        abort_if($post->section_id !== $section->id, 403, 'You cannot moderate posts outside your section.');
    }

    /**
     * GET /dashboard/posts
     * Show all posts in the admin's section, with optional type filter.
     */
    public function index(Request $request)
    {
        $section    = $this->adminSection();
        $typeFilter = $request->get('type', '');   // '' | 'text' | 'image' | 'video' | 'audio'

        $posts = Post::with('user')
            ->inSection($section->id)
            ->when($typeFilter, fn ($q) => $q->ofType($typeFilter))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // Counts per type for the filter pills
        $counts = [
            'all'   => Post::inSection($section->id)->count(),
            'text'  => Post::inSection($section->id)->ofType('text')->count(),
            'image' => Post::inSection($section->id)->ofType('image')->count(),
            'video' => Post::inSection($section->id)->ofType('video')->count(),
            'audio' => Post::inSection($section->id)->ofType('audio')->count(),
        ];

        return view('admin.posts', compact('posts', 'section', 'typeFilter', 'counts'));
    }

    /**
     * DELETE /dashboard/posts/{post}
     * Soft-delete a post. Only the admin responsible for that section can do this.
     */
    public function destroy(Post $post)
    {
        $this->authorizePost($post);

        $post->update([
            'deleted_by'           => auth()->id(),
            'deleted_at_by_admin'  => now(),
            'status'               => 'rejected',
        ]);

        $post->delete(); // soft delete

        return back()->with('success', 'Post has been removed from the section.');
    }

    /**
     * POST /dashboard/posts/{post}/approve
     * Approve a pending post.
     */
    public function approve(Post $post)
    {
        $this->authorizePost($post);

        $post->update(['status' => 'approved']);

        return back()->with('success', 'Post has been approved.');
    }
}