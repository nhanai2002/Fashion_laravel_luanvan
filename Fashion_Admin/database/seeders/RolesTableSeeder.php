<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('roles')->count() === 0) {
            DB::table('roles')->insert([
                ['id' => 1, 'name' => 'Quản trị viên'],
                ['id' => 2, 'name' => 'Khách hàng'],
            ]);
        }
    }
}
