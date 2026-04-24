<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberReport extends Model
{
    use HasFactory;

    protected $table = 'complaints';

    protected $fillable = [
        'user_id',
        'type',     // admin | member
        'subject',
        'body',
        'priority', // low | medium | high | critical
        'status',   // new | pending | in_progress | resolved | closed
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    /** The user who submitted the complaint */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** The manager/admin who resolved it */
    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function isResolved(): bool
    {
        return in_array($this->status, ['resolved', 'closed']);
    }
}