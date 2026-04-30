<?php

namespace App\Http\Controllers\Member;

use App\Models\Post;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class PostCreateController extends Controller
{
    
    public function create()
    {
        
        $section = auth()->user()->section;

        return view('member.createpost', compact('section'));
    }

    
    public function store(Request $request)
    {
        $user = auth()->user();

      
        $type = $this->resolveType($request);

       
        $rules = [
            'body'      => ['nullable', 'string', 'max:5000'],
            'privacy'   => ['required', 'in:public,friends'],
            'allow_duets' => ['nullable', 'boolean'],
        ];

        if ($type === 'image') {
            $rules['media'] = ['required', 'file', 'image', 'max:10240', 'mimes:jpg,jpeg,png,webp,gif'];
        } elseif ($type === 'video') {
            $rules['media'] = ['required', 'file', 'max:102400', 'mimes:mp4,mov,avi,webm'];
        } elseif ($type === 'audio') {
            $rules['media'] = ['required', 'file', 'max:20480', 'mimes:mp3,wav,ogg,m4a,aac'];
        } else {
           
            $rules['body'] = ['required', 'string', 'min:1', 'max:5000'];
        }

        $validated = $request->validate($rules);

      
        $mediaPath     = null;
        $mediaFilename = null;
        $mediaMime     = null;
        $mediaSize     = null;

        if ($request->hasFile('media') && $request->file('media')->isValid()) {
            $file          = $request->file('media');
            $mediaPath     = $file->store("posts/{$type}s", 'public');
            $mediaFilename = $file->getClientOriginalName();
            $mediaMime     = $file->getMimeType();
            $mediaSize     = $file->getSize();
        }

        
        if ($type === 'text' && empty($validated['body'])) {
            return back()->withErrors(['body' => 'Please write something before publishing.'])->withInput();
        }

        
        $sectionId = $user->section_id
            ?? Section::first()?->id;

        abort_if(!$sectionId, 422, 'You are not assigned to a section. Contact your admin.');

        Post::create([
            'user_id'        => $user->id,
            'section_id'     => $sectionId,
            'type'           => $type,
            'body'           => $validated['body'] ?? null,
            'media_path'     => $mediaPath,
            'media_filename' => $mediaFilename,
            'media_mime'     => $mediaMime,
            'media_size'     => $mediaSize,
            'status'         => 'pending',  
        ]);

        return redirect()->route('activemember.feed')->with('success', 'Your post has been submitted and is pending approval.');
    }

   
    private function resolveType(Request $request): string
    {
        if ($request->hasFile('media')) {
            $mime = $request->file('media')->getMimeType();
            if (str_starts_with($mime, 'image/'))  return 'image';
            if (str_starts_with($mime, 'video/'))  return 'video';
            if (str_starts_with($mime, 'audio/'))  return 'audio';
        }

       
        $explicit = $request->input('type');
        if (in_array($explicit, ['image', 'video', 'audio', 'text'])) {
            return $explicit;
        }

        return 'text';
    }
}