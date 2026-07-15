<?php

namespace App\Models;

use Database\Factories\FacultyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'code', 'description', 'is_active'])]
class Faculty extends Model
{
    /** @use HasFactory<FacultyFactory> */
    use HasFactory;

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function elections(): HasMany
    {
        return $this->hasMany(Election::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'faculty', 'name');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
