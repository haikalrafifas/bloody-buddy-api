<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // public function run(): void
    // {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    // }

    public function run(){
        // Get all seeder files in the seeders directory
        $seederFiles = File::files(database_path('seeders'));

        foreach ($seederFiles as $seederFile) {
            $seederClass = pathinfo($seederFile, PATHINFO_FILENAME);

            // Check if the seeder is not DatabaseSeeder itself
            if ($seederClass !== 'DatabaseSeeder') {
                echo 'Database\\Seeders\\' . $seederClass;
                $this->call('Database\\Seeders\\' . $seederClass);
            }
        }
    }
}
