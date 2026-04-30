<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AdminReport extends Model
{
    use HasFactory;

    protected $table = 'admin_reports';

    protected $fillable = [
        'admin_id',
        'category',           
        'description',
        'attachment_path',
        'attachment_filename',
        'attachment_mime',
        'status',             
        'manager_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

   
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

   
    public function attachmentUrl(): ?string
    {
        return $this->attachment_path ? Storage::url($this->attachment_path) : null;
    }

    
    public function categoryLabel(): string
    {
        return match($this->category) {
            'security'     => 'Security',
            'user_conduct' => 'User Conduct',
            'technical'    => 'Technical',
            'other'        => 'Other',
            default        => ucfirst($this->category),
        };
    }

    public function isResolved(): bool
{
    return in_array($this->status, ['reviewed', 'dismissed']);
}

    
    public function categoryIcon(): string
    {
        return match($this->category) {
            'security'     => 'security',
            'user_conduct' => 'person_off',
            'technical'    => 'cloud_off',
            'other'        => 'more_horiz',
            default        => 'report_problem',
        };
    }

   
    public function statusBadgeClass(): string
    {
        return match($this->status) {
            'pending'   => 'bg-amber-100 text-amber-700',
            'reviewed'  => 'bg-green-100 text-green-700',
            'dismissed' => 'bg-surface-container-highest text-on-surface-variant',
            default     => 'bg-surface-container text-on-surface-variant',
        };
    }
}