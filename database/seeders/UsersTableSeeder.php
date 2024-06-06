<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
        ['uuid' => Str::uuid()->toString(), 'username' => 'Administrator', 'email' => 'admin@localhost', 'password' => bcrypt('123456'), 'is_admin' => 1],
        ['uuid' => Str::uuid()->toString(), 'username' => 'User Pertama', 'email' => 'user1@localhost', 'password' => bcrypt('123456'), 'is_admin' => 0],
        ['uuid' => Str::uuid()->toString(), 'username' => 'User Kedua', 'email' => 'user2@localhost', 'password' => bcrypt('123456'), 'is_admin' => 0],
        ]);
    }
}
