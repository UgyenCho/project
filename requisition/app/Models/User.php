<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // If using Sanctum
// use Laravel\Jetstream\HasProfilePhoto; // If using Jetstream profile photos
// use Laravel\Fortify\TwoFactorAuthenticatable; // If using Fortify two-factor
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Added for relationship type hint
use Illuminate\Database\Eloquent\Relations\HasMany;   // Added for relationship type hint

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    // use HasProfilePhoto; // Uncomment if using Jetstream profile photos
    // use TwoFactorAuthenticatable; // Uncomment if using Fortify two-factor

    // --- Define Role Constants ---
    public const ROLE_LRC = 'LRC';
    public const ROLE_HOD = 'HOD';
    public const ROLE_FINANCE = 'FINANCE';
    public const ROLE_PRESIDENT = 'PRESIDENT';
    public const ROLE_STORE = 'STORE';
    public const ROLE_ADMIN = 'ADMIN'; // Or whatever string you use for Admin role in the DB
    // --- End Role Constants ---


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id', // Add department_id
        'role',          // Add role
        'employee_id',   // Add employee_id
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        // 'two_factor_recovery_codes', // Uncomment if using Fortify two-factor
        // 'two_factor_secret',      // Uncomment if using Fortify two-factor
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Use 'hashed' cast for automatic hashing
    ];

    // Uncomment if using Jetstream profile photos
    // /**
    //  * The accessors to append to the model's array form.
    //  *
    //  * @var array<int, string>
    //  */
    // protected $appends = [
    //     'profile_photo_url',
    // ];

    /**
     * Get the department that the user belongs to.
     */
    public function department(): BelongsTo // Added return type hint
    {
        // Assumes your Department model is App\Models\Department
        return $this->belongsTo(Department::class);
    }

    // --- Add Requisition Relationship (Optional but good practice) ---
    /**
     * Get the requisitions created by the user.
     */
    public function requisitions(): HasMany // Added return type hint
    {
        return $this->hasMany(Requisition::class);
    }
    // --- End Requisition Relationship ---


    // --- Add Role Checking Helper Methods ---

    /**
     * Check if the user has the LRC role.
     */
    public function isLrc(): bool
    {
        return $this->role === self::ROLE_LRC;
    }

    /**
     * Check if the user has the HOD role.
     */
    public function isHod(): bool
    {
        return $this->role === self::ROLE_HOD;
    }

    /**
     * Check if the user has the Finance role.
     */
    public function isFinance(): bool
    {
        return $this->role === self::ROLE_FINANCE;
    }

    /**
     * Check if the user has the President role.
     */
    public function isPresident(): bool
    {
        return $this->role === self::ROLE_PRESIDENT;
    }

    /**
     * Check if the user has the Store role.
     */
     public function isStore(): bool
    {
        return $this->role === self::ROLE_STORE;
    }

    /**
     * Check if the user has the Admin role.
     */
     public function isAdmin(): bool
     {
         return $this->role === self::ROLE_ADMIN;
     }

    // --- End Role Checking Helper Methods ---

}