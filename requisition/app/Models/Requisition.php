<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\User; // Assuming User model exists
use App\Models\RequisitionItem; // Assuming RequisitionItem model exists

class Requisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',              // The LRC user submitting
        'department_id',           // <<< THIS IS PRESENT!
        'requisition_date',
        'requester_name',
        'requester_designation', // The integer ID
        'status',               // The enum string value
        'remarks',              // Optional remarks field
    ];

    protected $casts = [
        'requisition_date' => 'date',
    ];

    // Relationship to the User (LRC) who created it
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function department() // <--- Must be exactly this name
    {
        return $this->belongsTo(Department::class);
    }

    // Relationship to its items
    public function items()
    {
        return $this->hasMany(RequisitionItem::class);
    }

    // Accessor for Status Badge Class
    protected function statusBadgeClass(): Attribute
    {
        return Attribute::make(
            get: function () {
                switch (strtolower($this->status ?? '')) {
                    case 'waiting for hod approval':
                    case 'waiting for finance approval':
                    case 'waiting for president approval':
                        return 'bg-warning text-dark';
                    case 'waiting for store manager action':
                        return 'bg-info';
                    case 'rejected by hod':
                    case 'rejected by finance':
                    case 'rejected by president':
                    case 'cancelled by lrc':
                        return 'bg-danger';
                    case 'approved':
                        return 'bg-success';
                    default:
                        return 'bg-secondary';
                }
            }
        );
    }

    // Accessor for Designation Text
    protected function designationText(): Attribute
    {
        return Attribute::make(
            get: function () {
                // --- IMPORTANT: Update this mapping to match your form's integer values ---
                $designations = [
                   1 => 'President',
                   2 => 'Dean',
                   3 => 'Head of Department (HOD)',
                   4 => 'Lecturer',
                   5 => 'Associate Lecturer',
                   6 => 'Assistant Lecturer',
                   7 => 'Lab Technician/Assistant',
                   8 => 'Librarian/Assistant',
                   9 => 'Admin Officer',
                   10 => 'Accounts Officer/Assistant',
                   11 => 'Store Keeper/Assistant',
                   12 => 'Estate Manager',
                   13 => 'Dean Student Affairs (DSA)',
                   14 => 'LRC', // Should this map to an integer? Maybe 7 or 8 depending on role?
                   15 => 'Technician',
                   16 => 'General Staff',
                   99 => 'Other',
                ];
                return $designations[$this->requester_designation] ?? 'Unknown'; // Use the integer value
            }
        );
    }
}