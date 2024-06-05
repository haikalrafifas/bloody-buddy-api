<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'donor_id', 'schedule_id', 'status_id',
    ];

    protected $hidden = [
        'id', 'donor_id', 'schedule_id', 'status_id',
    ];

    protected $casts = [];

    public function donorApplicant()
    {
        return $this->belongsTo(DonorApplicant::class, 'donor_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function donorStatus()
    {
        return $this->belongsTo(DonorStatus::class, 'status_id');
    }

    public static function checkQuota(int $schedule_id)
    {
        $schedule = Schedule::find($schedule_id);
        if ( !$schedule ) {
            throw new \Exception("Schedule not found.");
        }

        $currentCount = self::where('schedule_id', $schedule_id)->count();
        if ( $currentCount > $schedule->daily_quota ) {
            throw new \Exception("Daily quota exceeded for this schedule.");
        }

        return true;
    }
}
