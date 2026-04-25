<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [ 'img/pizza_plus.png','img/italian_pizza.jpg','/img/pizza_cheese.jpg','img/pizza_burger.png' ];


        DB::table( 'products' )->insert([
            'name' => Str::random(10),
            'image' => $images[rand(0,3)],
            'price' => rand(100, 500),
            'description' => Str::random(150),
            'recipe' => Str::random(50),
            'short_desc' => Str::random(50),
            'category' => rand(1,2),
            'stock' => 57,
            'active' => rand(0,1),
            'status' => 1,
        ]);

        $count = 0;
        while( $count != 49 ){
            DB::table( 'products' )->insert([
                'name' => Str::random(10),
                'image' => $images[rand(0,3)],
                'price' => rand(100, 500),
                'description' => Str::random(150),
                'recipe' => Str::random(50),
                'short_desc' => Str::random(50),
                'category' => rand(1,2),
                'stock' => rand(1,15),
                'active' => rand(0,1),
                'status' => rand(1,3),
            ]);
            $count++;
        }

        DB::table( 'product_statuses' )->insert([
            'status_name' => 'active',
        ]);

        DB::table( 'product_statuses' )->insert([
            'status_name' => 'inactive',
        ]);

        DB::table( 'product_statuses' )->insert([
            'status_name' => 'sold out',
        ]);
    }
}
