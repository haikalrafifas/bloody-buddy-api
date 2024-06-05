<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonorApplicant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid', 'user_id', 'schedule_id', 'status_id', 'name', 'nik', 'dob',
        'gender', 'blood_type', 'phone_number', 'address', 'body_mass',
        'hemoglobin_level', 'blood_pressure', 'medical_conditions',
    ];

    protected $hidden = [
        'id', 'user_id', 'schedule_id', 'status_id',
    ];

    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    
    public function status()
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
        if ( $currentCount >= $schedule->daily_quota ) {
            throw new \Exception("Daily quota exceeded for this schedule.");
        }

        return true;
    }
}
