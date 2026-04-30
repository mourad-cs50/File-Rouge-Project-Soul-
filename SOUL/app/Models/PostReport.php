<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'reported_by',
        'reason',       
        'details',
        'status',       
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class)->withTrashed();
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

   

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInSection($query, int $sectionId)
    {
        return $query->whereHas('post', fn ($q) => $q->where('section_id', $sectionId));
    }

   
    
    public function reasonLabel(): string
    {
        return match($this->reason) {
            'spam'              => 'Spam',
            'harassment'        => 'Harassment',
            'inappropriate'     => 'Inappropriate',
            'false_information' => 'False Information',
            'other'             => 'Other',
            default             => ucfirst($this->reason),
        };
    }

    
    public function reasonBadgeClass(): string
    {
        return match($this->reason) {
            'harassment'        => 'bg-error-container text-on-error-container',
            'spam',
            'false_information' => 'bg-secondary-container text-on-secondary-container',
            'inappropriate'     => 'bg-tertiary-container text-on-tertiary-container',
            default             => 'bg-surface-container-highest text-on-surface-variant',
        };
    }

    
    public function riskLabel(): string
    {
        return match($this->reason) {
            'harassment' => 'High Risk',
            'spam',
            'false_information' => 'Spam',
            'inappropriate' => 'Inappropriate',
            default => 'Flagged',
        };
    }
}