<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Schedule;

class SchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schedule::insert([
        ['uuid' => Str::uuid()->toString(), 'location_id' => 1, 'daily_quota' => 100, 'start_date' => '2024-06-06 00:00:00', 'end_date' => '2024-06-10 00:00:00'],
        ['uuid' => Str::uuid()->toString(), 'location_id' => 2, 'daily_quota' => 1, 'start_date' => '2024-06-06 00:00:00', 'end_date' => '2024-06-10 00:00:00'],
        ]);
    }
}
