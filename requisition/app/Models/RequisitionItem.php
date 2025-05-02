<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Requisition; // Import Requisition

class RequisitionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_id',
        'item_name',
        'item_description',
        'item_quantity',
        'item_remarks',
    ];

    // Relationship back to the Requisition
    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }
}