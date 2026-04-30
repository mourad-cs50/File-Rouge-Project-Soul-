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
    
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'public'); 

        $posts = Post::with(['user', 'section'])
            ->withCount(['likes', 'comments'])
            ->where('status', 'approved')
            ->when($filter === 'friends', function ($q) {
                $friendIds = auth()->user()->friends()->pluck('id');
                $q->whereIn('user_id', $friendIds);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('member.feed', compact('posts', 'filter'));
    }

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

        
        if (request()->expectsJson()) {
            return response()->json(['liked' => $liked, 'count' => $count]);
        }

        return back();
    }

  
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

    
    public function report(Request $request, Post $post)
    {
        $request->validate([
            'reason'  => ['required', 'in:spam,harassment,inappropriate,false_information,other'],
            'details' => ['nullable', 'string', 'max:500'],
        ]);

        
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