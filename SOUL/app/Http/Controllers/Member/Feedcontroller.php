<?php

namespace App\Http\Controllers\Member;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\PostReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedController extends Controller
{
    /**
     * GET /feed
     * Show the curated member feed (approved posts only).
     * Supports public / friends filter.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'public'); // 'public' | 'friends'

        $posts = Post::with(['user', 'section'])
            ->withCount(['likes', 'comments'])
            ->where('status', 'approved')
            ->when($filter === 'friends', function ($q) {
                // Show posts only from users the auth user follows/friends
                // Replace with your actual friends/follow relationship
                $friendIds = auth()->user()->friends()->pluck('id');
                $q->whereIn('user_id', $friendIds);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('member.feed', compact('posts', 'filter'));
    }

    /**
     * POST /feed/{post}/like
     * Toggle like on a post.
     */
    public function like(Post $post)
    {
        $userId = auth()->id();

        $existing = PostLike::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            PostLike::create([
                'post_id' => $post->id,
                'user_id' => $userId,
            ]);
            $liked = true;
        }

        $count = PostLike::where('post_id', $post->id)->count();

        // Return JSON for AJAX, redirect for standard form
        if (request()->expectsJson()) {
            return response()->json(['liked' => $liked, 'count' => $count]);
        }

        return back();
    }

    /**
     * POST /feed/{post}/comment
     * Add a comment to a post.
     */
    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'body' => ['required', 'string', 'min:1', 'max:500'],
        ]);

        PostComment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'body'    => $request->body,
        ]);

        return back()->with('success', 'Comment added.');
    }

    /**
     * POST /feed/{post}/report
     * Report a post to the section admin for review.
     */
    public function report(Request $request, Post $post)
    {
        $request->validate([
            'reason'  => ['required', 'in:spam,harassment,inappropriate,false_information,other'],
            'details' => ['nullable', 'string', 'max:500'],
        ]);

        // Prevent double-reporting the same post
        $alreadyReported = PostReport::where('post_id', $post->id)
            ->where('reported_by', auth()->id())
            ->exists();

        if ($alreadyReported) {
            return back()->with('error', 'You have already reported this post.');
        }

        PostReport::create([
            'post_id'     => $post->id,
            'reported_by' => auth()->id(),
            'reason'      => $request->reason,
            'details'     => $request->details,
            'status'      => 'pending',
        ]);

        return back()->with('success', 'Post reported. Our admin team will review it shortly.');
    }
}