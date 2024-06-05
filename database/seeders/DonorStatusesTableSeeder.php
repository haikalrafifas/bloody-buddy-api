<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DonorStatuses;
use Illuminate\Support\Str;

class DonorStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DonorStatuses::insert([
        ['name' => 'Waiting List', 'description' => 'Form submitted, awaiting approval'],
        ['name' => 'Approved', 'description' => 'Eligible to donate'],
        ['name' => 'Ongoing', 'description' => 'Currently Ongoing'],
        ['name' => 'Done', 'description' => 'Donation Completed, Cooldown for 2 months'],
        ['name' => 'Rejected', 'description' => 'Request Rejected'],
        ['name' => 'Cancelled', 'description' => 'Request Cancelled'],
        ]);
    }
}
