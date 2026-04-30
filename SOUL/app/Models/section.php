<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tag',
        'admin_id',
    ];

    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

   
    public function members()
    {
        return $this->hasMany(User::class, 'section_id');
    }

   
    public function getMemberCountAttribute(): int
    {
        return $this->members()->count();
    }
}