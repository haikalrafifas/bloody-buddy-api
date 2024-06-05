<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $hidden = ['id'];

    protected $casts = [];

    public function donorSchedules()
    {
        return $this->hasMany(DonorSchedule::class, 'status_id');
    }
}
