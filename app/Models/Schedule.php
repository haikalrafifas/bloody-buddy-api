<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'location_id',
        'daily_quota',
        'start_date',
        'end_date',
    ];

    protected $hidden = ['id', 'location_id'];

    protected $casts = [];

    protected $appends = ['current_daily_quota'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function donorApplicants()
    {
        return $this->hasMany(DonorApplicant::class);
    }

    public function getCurrentDailyQuotaAttribute($schedule_id = 0)
    {
        // Only count current quota if the status is either Waiting List or Approved
        if ( $this->exists && $schedule_id !== 0 ) {
            return $this->donorApplicants()
            ->where('schedule_id', $schedule_id)
            ->whereIn('status_id', [1, 2, 3])
            ->count();
        }

        return null;
    }
}
