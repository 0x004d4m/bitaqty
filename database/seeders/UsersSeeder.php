<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(['id' => 1,'name' => "admin",'email' => "admin@app.com",'password' => Hash::make('admin1234')]);
        DB::table('languages')->insert([
            ['id' => 1, 'name' => "English", 'flag' => "", 'abbr' => "en", 'script' => "", 'native' => "English", 'active' => 1, 'default' => 1,],
            ['id' => 2, 'name' => "Arabic", 'flag' => "", 'abbr' => "ar", 'script' => "", 'native' => "العربية", 'active' => 1, 'default' => 0,],
        ]);
        DB::table('countries')->insert([
            ['id' => 1, 'name' => '{"en":"Jordan","ar":"الاردن"}', 'code' => "JO"],
            ['id' => 2, 'name' => '{"en":"United States Of America","ar":"الولايات المتحدة الامريكية"}', 'code' => "USA"],
        ]);
        DB::table('states')->insert([
            ['id' => 1, 'name' => '{"en":"Amman","ar":"عمان"}', 'country_id' => 1],
            ['id' => 2, 'name' => '{"en":"California","ar":"كاليفورنيا"}', 'country_id' => 2],
        ]);
        DB::table('currencies')->insert([
            ['id' => 1, 'name' => '{"en":"Jordanian Dinar","ar":"دينار اردني"}', 'symbol'=> 'JOD', 'to_jod'=>'1', 'country_id' => 1],
            ['id' => 2, 'name' => '{"en":"United States Doller","ar":"دولار امريكي"}', 'symbol'=> 'USD', 'to_jod'=>'0.71', 'country_id' => 2],
        ]);
    }
}
