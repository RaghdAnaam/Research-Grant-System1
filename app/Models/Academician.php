<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Academician extends Model
{
    use HasFactory;

    // Protect against mass-assignment vulnerabilities
    protected $fillable = [
        'name',
        'staff_number',
        'email',
        'college',
        'department',
        'position',
    ];

    public function grantsAsLeader()
    {
        return $this->hasMany(Grant::class, 'leader_id');
    }

    public function grantsAsMember()
{
    return $this->belongsToMany(Grant::class, 'academician_grant', 'academician_id', 'grant_id');
}

}
