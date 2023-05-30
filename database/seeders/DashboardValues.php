<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DashboardValues extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dashboard_values')->insert([
            ['id' => 1, 'name' => '{"en":"مجموع التحويلات المالية","ar":"مجموع التحويلات المالية"}', 'is_visible' => false],
            ['id' => 2, 'name' => '{"en":"مجموع الارباح","ar":"مجموع الارباح"}', 'is_visible' => false],
            ['id' => 3, 'name' => '{"en":"عدد التجار","ar":"عدد التجار"}', 'is_visible' => false],
            ['id' => 4, 'name' => '{"en":"عدد المستخدمين","ar":"عدد المستخدمين"}', 'is_visible' => false],
            ['id' => 5, 'name' => '{"en":"المنتجات الحالية","ar":"المنتجات الحالية"}', 'is_visible' => true],
            ['id' => 6, 'name' => '{"en":"المنتجات المباعة","ar":"المنتجات المباعة"}', 'is_visible' => true],
            ['id' => 7, 'name' => '{"en":"عمليات قيد الانتظار","ar":"عمليات قيد الانتظار"}', 'is_visible' => true],
            ['id' => 8, 'name' => '{"en":"طلبات شحن الرصيد","ar":"طلبات شحن الرصيد"}', 'is_visible' => true],
            ['id' => 9, 'name' => '{"en":"المستخدمين الجدد","ar":"المستخدمين الجدد"}', 'is_visible' => true],
            ['id' => 10, 'name' => '{"en":"الدعم الفني","ar":"الدعم الفني"}', 'is_visible' => true],
        ]);
    }
}
