<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public $limit = 10; // Was a 1999 but for tests changed it for 10
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = 0;

         while ($count < $this->limit) {
            DB::table('users')->insert([
                'username' => Str::random(10),
                'email' => Str::random(10) . '@gmail.com',
                'password' => Hash::make('password'),
                'is_admin' => 0,
            ]);

            $count++;
        }

        DB::table('users')->insert([
            'username' => 'test user',
            'email' => 'tests@gmail.com',
            'password' => Hash::make('password'),
            'is_admin' => 1,
        ]);
    }
}
