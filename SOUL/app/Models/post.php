<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'section_id',
        'type',            
        'body',
        'media_path',
        'media_filename',
        'media_mime',
        'media_size',
        'media_duration',
        'thumbnail_path',
        'status',           
        'deleted_by',
        'deleted_at_by_admin',
    ];

    protected $casts = [
        'deleted_at_by_admin' => 'datetime',
    ];

   

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function reports()
    {
        return $this->hasMany(PostReport::class);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class)->latest();
    }

    public function isLikedBy(int $userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }
    
   

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeInSection($query, int $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

   
    public function mediaUrl(): ?string
    {
        return $this->media_path ? Storage::url($this->media_path) : null;
    }

    
    public function thumbnailUrl(): ?string
    {
        return $this->thumbnail_path ? Storage::url($this->thumbnail_path) : null;
    }

  
    public function formattedSize(): string
    {
        if (!$this->media_size) return '—';
        $units = ['B', 'KB', 'MB', 'GB'];
        $size  = $this->media_size;
        $i     = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 1) . ' ' . $units[$i];
    }

    
    public function formattedDuration(): string
    {
        if (!$this->media_duration) return '';
        $m = intdiv($this->media_duration, 60);
        $s = $this->media_duration % 60;
        return sprintf('%d:%02d', $m, $s);
    }

    
    public function typeBadgeClass(): string
    {
        return match($this->type) {
            'image' => 'bg-white/90 text-primary',
            'video' => 'bg-inverse-surface/80 text-white',
            'audio' => 'bg-tertiary-container text-on-tertiary-container',
            'text'  => 'bg-secondary-fixed text-on-secondary-fixed',
            default => 'bg-surface-container text-on-surface-variant',
        };
    }

    
    public function typeLabel(): string
    {
        return match($this->type) {
            'image' => 'Image Content',
            'video' => 'Video Content',
            'audio' => 'Audio Content',
            'text'  => 'Text Only',
            default => ucfirst($this->type),
        };
    }
}