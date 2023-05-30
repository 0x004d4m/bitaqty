<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldTypes extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('field_types')->insert([
            ['id' => 1, 'name' => 'Number'],
            ['id' => 2, 'name' => 'Text'],
            ['id' => 3, 'name' => 'Decimal'],
            ['id' => 4, 'name' => 'Image'],
            ['id' => 5, 'name' => 'Rich Text'],
        ]);
    }
}
