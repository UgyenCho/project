<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // ** ACTION: Ensure this array is correct and matches create() calls **
    protected $fillable = [
        'user_id',      // The user receiving the notification
        'message',      // The notification text
        'link',         // Optional URL link
        'type',         // Optional category (e.g., 'new_requisition')
        'read_at',      // Timestamp when read (null if unread)
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}