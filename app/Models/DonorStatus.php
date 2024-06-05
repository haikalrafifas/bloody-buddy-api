<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonorStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $hidden = ['id'];

    protected $casts = [];

    public function donorApplicants()
    {
        return $this->hasMany(DonorApplicant::class);
    }
}
