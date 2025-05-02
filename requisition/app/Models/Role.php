<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // If defining inverse relationship

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the users associated with this role (optional inverse relationship).
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class); // Assumes you add role_id to users table
    }
}