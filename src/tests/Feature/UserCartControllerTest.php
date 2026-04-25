<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\ProductSeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserCartControllerTest extends TestCase
{

    use RefreshDatabase;

    public static function addToCartFailureProvider(): array
    {
        return [
            'set_one' => [150,10],
            'set_two' => [1,999]
        ];
    }

    public function testAddToCartSuccess(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $token    = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token['token']])->post('/api/cart/add',['product_id' => 1, 'product_amount' => 1]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status'  => 'success',
                'message' => 'Product added to cart'
            ]);
    }

    #[DataProvider('addToCartFailureProvider')]
    public function testAddToCartFail($id,$amount): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $token    = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token['token']])->post('/api/cart/add',['product_id' => $id, 'product_amount' => $amount]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'status'  => 'error',
                'message' => 'Cannot add product'
            ]);
    }

    public function testShowUserCart(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $token    = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token['token']])->post('/api/cart');


        $response
            ->assertStatus(200)
            ->assertJson([
                'cart'         => $response['cart'],
                'total_amount' => $response['total_amount'],
                'total_cost'   => $response['total_cost'],
            ]);
    }

    public function testShowUserCartUnauthorized(): void
    {
        $response = $this->post('/api/cart');

        $response
            ->assertStatus(401);
    }

    public function testRemoveFromCartSuccess(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $token    = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $this->withHeaders(['Authorization' => 'Bearer '.$token['token']])->post('/api/cart/add',['product_id' => 1, 'product_amount' => 1]);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token['token']])->delete('/api/cart/remove/',['product_id' => 1]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status'  => 'success',
                'message' => 'Product successfully removed'
            ]);
    }

    public function testRemoveFromCartFail(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $token    = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token['token']])->delete('/api/cart/remove/',['product_id' => 1]);

        $response
            ->assertStatus(404)
            ->assertJson([
                'status'  => 'error',
                'message' => 'Cannot delete product, product not found'
            ]);
    }
}
