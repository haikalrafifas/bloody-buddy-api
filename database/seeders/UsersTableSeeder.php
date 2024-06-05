<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
        ['name' => 'Administrator', 'email' => 'admin@localhost', 'password' => bcrypt('123456'), 'is_admin' => 1],
        ['name' => 'User Pertama', 'email' => 'user1@localhost', 'password' => bcrypt('123456'), 'is_admin' => 0],
        ['name' => 'User Kedua', 'email' => 'user2@localhost', 'password' => bcrypt('123456'), 'is_admin' => 0],
        ]);
    }
}
