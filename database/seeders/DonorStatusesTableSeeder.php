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
        ['name' => 'Waiting List', 'description' => 'Form submitted, awaiting approval', 'classname' => 'bg-yellow-400'],
        ['name' => 'Approved', 'description' => 'Eligible to donate', 'classname' => 'bg-green-600'],
        ['name' => 'Ongoing', 'description' => 'Currently Ongoing', 'classname' => 'bg-blue-600'],
        ['name' => 'Done', 'description' => 'Donation Completed, Cooldown for 2 months', 'classname' => 'bg-[#FF2400]'],
        ['name' => 'Rejected', 'description' => 'Request Rejected', 'classname' => 'bg-red-700'],
        ['name' => 'Cancelled', 'description' => 'Request Cancelled', 'classname' => 'bg-red-800'],
        ]);
    }
}
