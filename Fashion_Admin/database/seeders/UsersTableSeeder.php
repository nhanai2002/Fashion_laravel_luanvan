<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'mailtousetest1@gmail.com',
            'password' => bcrypt(123456),
            'role_id' => 1,
            'created_at' => now(),  
            'updated_at' => now(),
        ]);
    }
}
