<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $table = 'donor_applicants';

    protected $fillable = [
        'uuid', 'user_id', 'status_id', 'name', 'nik', 'dob', 'gender', 'blood_type',
        'phone_number', 'address', 'body_mass', 'hemoglobin_level', 'blood_pressure',
        'medical_conditions',
    ];

    protected $hidden = [
        'id', 'user_id', 'status_id',
    ];

    protected $casts = [];
}
