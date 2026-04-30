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
        'type',     
        'subject',
        'body',
        'priority', 
        'status',  
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

  
    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

 
    public function isResolved(): bool
    {
        return in_array($this->status, ['resolved', 'closed']);
    }
}