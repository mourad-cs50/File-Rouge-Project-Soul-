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
        'category',           // security | user_conduct | technical | other
        'description',
        'attachment_path',
        'attachment_filename',
        'attachment_mime',
        'status',             // pending | reviewed | dismissed
        'manager_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    /** The admin who submitted the report */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /** Public URL to the attachment */
    public function attachmentUrl(): ?string
    {
        return $this->attachment_path ? Storage::url($this->attachment_path) : null;
    }

    /** Human-readable category label */
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

    /** Material icon name per category */
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

    /** Tailwind classes for status badge */
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