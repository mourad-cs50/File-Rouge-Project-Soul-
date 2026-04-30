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

    /**
     * The user assigned as admin of this section.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * All members that belong to this section.
     */
    public function members()
    {
        return $this->hasMany(User::class, 'section_id');
    }

    /**
     * Member count accessor.
     */
    public function getMemberCountAttribute(): int
    {
        return $this->members()->count();
    }
}