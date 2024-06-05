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
        if ( $this->exists && $schedule_id !== 0 ) {
            return $this->donorApplicants()->where('schedule_id', $schedule_id)->count();
        }

        return null;
    }
}
