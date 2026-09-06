<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    public const STATUSES = [
        'Pending',
        'In Progress',
        'Completed',
    ];

    protected $fillable = [
        'grant_id',
        'milestone_name',
        'target_completion_date',
        'deliverable',
        'status',
        'remarks',
    ];

    public function grant()
    {
        return $this->belongsTo(Grant::class, 'grant_id');
    }

    public static function statuses(): array
    {
        return self::STATUSES;
    }

    public function displayStatus(): string
    {
        return in_array($this->status, self::STATUSES, true)
            ? $this->status
            : 'Pending';
    }
}
