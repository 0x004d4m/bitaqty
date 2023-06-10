<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('credit_statuses')->insert([
            ['id' => 1, 'name' => '{"en":"Pending","ar":"قيد الانتظار"}'],
            ['id' => 2, 'name' => '{"en":"Accepted","ar":"مقبول"}'],
            ['id' => 3, 'name' => '{"en":"Rejected","ar":"مرفوض"}'],
        ]);
        DB::table('credit_types')->insert([
            ['id' => 1, 'name' => '{"en":"Bank Transfere","ar":"حوالة بنكية"}'],
            ['id' => 2, 'name' => '{"en":"QR","ar":"QR"}'],
            ['id' => 3, 'name' => '{"en":"Prepaid","ar":"بطاقات مدفوعة مسبقا"}'],
        ]);
        DB::table('issue_types')->insert([
            ['id' => 1, 'name' => '{"en":"Technical Issue","ar":"مشاكل تقنية"}'],
        ]);
        DB::table('order_statuses')->insert([
            ['id' => 1, 'name' => '{"en":"Pending","ar":"قيد الانتظار"}'],
            ['id' => 2, 'name' => '{"en":"Accepted","ar":"مقبول"}'],
            ['id' => 3, 'name' => '{"en":"Rejected","ar":"مرفوض"}'],
        ]);
        DB::table('types')->insert([
            ['id' => 1, 'name' => '{"en":"Cards","ar":"الشحن المباشر"}', 'image'=>'', 'need_approval'=>0, 'is_active'=>1],
            ['id' => 2, 'name' => '{"en":"Games","ar":"الالعاب"}', 'image'=>'', 'need_approval'=>1, 'is_active'=>1],
            ['id' => 3, 'name' => '{"en":"Bills","ar":"الفواتير"}', 'image'=>'', 'need_approval'=>1, 'is_active'=>1],
        ]);
    }
}
