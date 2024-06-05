<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;
use Illuminate\Support\Str;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::insert([
        ['uuid' => Str::uuid()->toString(), 'name' => 'Jakarta Blood Donation Center', 'address' => 'Jl. Medan Merdeka', 'image' => 'locations/aduifhacd.webp'],
        ['uuid' => Str::uuid()->toString(), 'name' => 'Depok Puskesmas', 'address' => 'Jl. Depok Raya', 'image' => 'locations/aduivss.webp'],
        ]);
    }
}
