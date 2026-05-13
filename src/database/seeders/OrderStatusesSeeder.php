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

        $statuses = [['status_name'=>'preparing'],['status_name'=>'ready to delivery'],['status_name'=>'on the way'],['status_name'=>'delivered'],['status_name'=>'cancelled']];

        DB::table('order_statuses')->insert($statuses);
    }
}
