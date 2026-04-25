<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class OrderStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $statuses = ['preparing','ready to delivery','on the way','delivered','cancelled'];

        foreach ($statuses as $status) {
            DB::table('order_statuses')->insert([
                'status_name' => $status,
            ]);
        }

    }
}
