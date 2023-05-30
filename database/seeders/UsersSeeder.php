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
    }
}
