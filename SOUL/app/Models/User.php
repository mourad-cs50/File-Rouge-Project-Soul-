<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',       
        'status',     
        'section_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];


  
protected static function booted()
{
    static::creating(function ($user) {
        if ($user->role === 'manager') {
            $user->status = 'active';
        }
    });

    static::updating(function ($user) {
        if ($user->role === 'manager') {
            $user->status = 'active';
        }
    });
}

   
    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isManager(): bool { return $this->role === 'manager'; }

    
    public function isActive(): bool  { return $this->status === 'active'; }
    public function isPending(): bool { return $this->status === 'pending'; }
    public function isBanned(): bool  { return $this->status === 'banned'; }

   
    public function statusBadgeClass(): string
    {
        return match($this->status) {
            'active'  => 'bg-green-100 text-green-700',
            'banned'  => 'bg-error-container/20 text-on-error-container',
            'pending' => 'bg-surface-container-highest text-on-surface-variant',
            default   => 'bg-surface-container-highest text-on-surface-variant',
        };
    }

   
    public function statusDotClass(): string
    {
        return match($this->status) {
            'active'  => 'bg-green-500',
            'banned'  => 'bg-error',
            default   => 'bg-outline',
        };
    }

    
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function administeredSections()
    {
        return $this->hasMany(Section::class, 'admin_id');
    }
}