<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// IMPORTANT: Use Model, NOT Authenticatable if these users CANNOT log in via standard auth
use Illuminate\Database\Eloquent\Model;
// Use Authenticatable if you ARE building custom separate login for LRCs
// use Illuminate\Foundation\Auth\User as Authenticatable;

// class Lrc extends Authenticatable // Use this line if building custom Lrc login
class Lrc extends Model // Use this line if Lrc records are just data, not login accounts
{
    use HasFactory; // Add other necessary traits like Notifiable if needed

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lrc'; // Explicitly define table name

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     * Needed if extending Authenticatable and using passwords
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        // 'remember_token', // Add if you include rememberToken() in migration
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime', // Add if you include email verification
        'password' => 'hashed', // Automatically hash passwords on set if using Authenticatable
    ];

    /**
     * Get the department that owns the LRC record.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}