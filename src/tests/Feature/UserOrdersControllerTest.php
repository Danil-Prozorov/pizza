<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\OrderStatusesSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserOrdersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testJsonOrderList(): void
    {
        $this->seed(UserSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' =>'Bearer '.$user['token']])->post('/api/order');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'orders_list' => $response['orders_list'],
                'orders_products' => $response['orders_products'],
            ]);
    }

    public function testMakeOrderSuccess(): void
    {
        $this->seed(ProductSeeder::class);
        $this->seed(UserSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/cart/add',['product_id' => 1, 'product_amount' => 1]);
        $response = $this->withHeaders(['Authorization' =>'Bearer '.$user['token']])->post('/api/order/create',['address' => 'test address']);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status'  => 'success',
                'message' => 'Order created successfully'
            ]);
    }

    public function testMakeOrderFail(): void
    {
        $this->seed(ProductSeeder::class);
        $this->seed(UserSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' =>'Bearer '.$user['token']])->post('/api/order/create',['address' => 'address']);

        $response
            ->assertStatus(400)
            ->assertJson([
                'status'=>'error',
                'message' => 'Cannot create an order'
            ]);
    }

    public function testShowOrderSuccess(): void
    {
        $this->seed(ProductSeeder::class);
        $this->seed(OrderStatusesSeeder::class);
        $this->seed(UserSeeder::class);

        $user  = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/cart/add',['product_id' => 1, 'product_amount' => 1]);
        $this->withHeaders(['Authorization' =>'Bearer '.$user['token']])->post('/api/order/create',['address' => 'test address']);
        $response = $this->withHeaders(['Authorization' =>'Bearer '.$user['token']])->post('/api/order/1');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => $response['data'],
            ]);
    }

    public function testShowOrderFail(): void
    {
        $this->seed(UserSeeder::class);

        $user  = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' =>'Bearer '.$user['token']])->post('/api/order/125');

        $response
            ->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => $response['message'],
            ]);
    }

    public function testCancelOrderSuccess(): void
    {
        $this->seed(ProductSeeder::class);
        $this->seed(OrderStatusesSeeder::class);
        $this->seed(UserSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/cart/add',['product_id' => 1, 'product_amount' => 1]);
        $this->withHeaders(['Authorization' =>'Bearer '.$user['token']])->post('/api/order/create',['address' => 'test address']);
        $response = $this->withHeaders(['Authorization' =>'Bearer '.$user['token']])->delete('/api/order/1');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Order deleted successfully',
                'order_data' => $response['order_data'],
            ]);
    }
}
