<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Maintenances extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('maintenances')->insert([
            ['id' => 1, 'type' => 'Maintenance Mode', 'is_active' => false]
        ]);
    }
}
