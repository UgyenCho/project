<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public function lrcs()
{
    return $this->hasMany(Lrc::class);
}

public function hods()
{
    return $this->hasMany(Hod::class);
}
public function department()
{
    // Assumes 'department_id' is the foreign key in 'requisitions' table
    return $this->belongsTo(Department::class);
}
}
