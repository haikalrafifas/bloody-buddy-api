<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'location_id',
        'daily_quota',
        'start_date',
        'end_date',
    ];

    protected $hidden = ['id'];

    protected $casts = [];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function donorSchedules()
    {
        return $this->hasMany(DonorSchedule::class, 'schedule_id');
    }
}
