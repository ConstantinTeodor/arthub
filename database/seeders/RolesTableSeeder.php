<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            'name' => 'admin',
            'description' => 'Administrator Role',
        ]);

        DB::table('roles')->insert([
            'name' => 'super-user',
            'description' => 'Super User Role',
        ]);

        DB::table('roles')->insert([
            'name' => 'user',
            'description' => 'User Role',
        ]);
    }
}
