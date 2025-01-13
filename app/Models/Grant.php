<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grant extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_title',
        'grant_provider',
        'leader_id',
        'grant_amount',
        'start_date', 
        'duration_months',
    ];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'academician_grant', 'grant_id', 'academician_id');
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'grant_id');
    }
}




