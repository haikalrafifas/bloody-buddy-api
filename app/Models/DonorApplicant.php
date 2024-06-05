<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorApplicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'user_id', 'status_id', 'name', 'nik', 'dob', 'gender', 'blood_type',
        'phone_number', 'address', 'body_mass', 'hemoglobin_level', 'blood_pressure',
        'medical_conditions',
    ];

    protected $hidden = [
        'id', 'user_id', 'status_id', 'schedule_id',
    ];

    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function donorSchedules()
    {
        return $this->hasMany(DonorSchedule::class, 'donor_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'scheduleid');
    }
    
    public function donorStatuses()
    {
        return $this->hasMany(DonorStatus::class, 'id');
    }
}
