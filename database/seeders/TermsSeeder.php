<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('terms')->insert([
            ['id' => 1, 'name' => '{"en":"Clients Terms And Conditions","ar":"الشروط والاحكام للزبآئن"}', 'term' => '{"en":"","ar":""}'],
            ['id' => 2, 'name' => '{"en":"Clients Privacy Policy","ar":"سياسة الخصوصية للزبآن"}', 'term' => '{"en":"","ar":""}'],
            ['id' => 3, 'name' => '{"en":"Vendors Terms And Conditions","ar":"الشروط والاحكام للتجار"}', 'term' => '{"en":"","ar":""}'],
            ['id' => 4, 'name' => '{"en":"Vendors Privacy Policy","ar":"سياسة الخصوصية للتجار"}', 'term' => '{"en":"","ar":""}'],
        ]);
    }
}
