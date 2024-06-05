<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'address',
        'image',
    ];

    protected $hidden = ['id'];

    protected $casts = [];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'location_id');
    }
}
