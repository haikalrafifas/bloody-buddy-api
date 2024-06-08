<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\DonorStatus;

class DonorStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DonorStatus::insert([
        ['name' => 'Waiting List', 'description' => 'Form submitted, awaiting approval'],
        ['name' => 'Approved', 'description' => 'Eligible to donate'],
        ['name' => 'Ongoing', 'description' => 'Currently Ongoing'],
        ['name' => 'Done', 'description' => 'Donation Completed, Cooldown for 2 months'],
        ['name' => 'Rejected', 'description' => 'Request Rejected'],
        ['name' => 'Cancelled', 'description' => 'Request Cancelled'],
        ]);
    }
}
