<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\ProductSeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class AdminProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShowProducts(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/products');

        $response->assertStatus(200);
    }

    public function testCreateProductSuccess(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/products/create',[
            'name' => 'test product name',
            'price' => 150,
            'description' => 'test description',
            'recipe' => 'prod recipe',
            'short_desc' => 'short description',
            'category' => 2,
            'stock' => 200,
            'active' => 1,
            'status' => 1,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Product created'
            ]);
    }

    public function testCreateProductFailGet302Response(): void // Incorrect params (name is number) for creating product will be filtered inside it's request
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/products/create',[
            'name' => 12,
            'price' => 150,
            'description' => 'test description',
            'recipe' => 'prod recipe',
            'short_desc' => 'short description',
            'category' => 2,
            'stock' => 200,
            'active' => 2,
            'status' => 2,
        ]);

        $response->assertStatus(302);
    }

    public function testShowProductSuccess(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/products/1');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'product' => $response['product']
            ]);
    }

    public function testShowProductFail(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/products/0');

        $response
            ->assertStatus(404)
            ->assertJson([
                'status'=>'error',
                'message'=>'Product not found'
            ]);
    }

    public function testUpdateProductSuccess(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->put('/api/admin/products/1',[
            'name' => 'test product name',
            'price' => 350,
            'description' => 'test description',
            'recipe' => 'prod recipe2',
            'short_desc' => 'short description',
            'category' => 2,
            'stock' => 250,
            'active' => 1,
            'status' => 1,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Product updated'
            ]);
    }

    public function testUpdateProductFail(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->put('/api/admin/products/0',[
            'name' => 'test product name',
            'price' => 350,
            'description' => 'test description',
            'recipe' => 'prod recipe2',
            'short_desc' => 'short description',
            'category' => 2,
            'stock' => 250,
            'active' => 1,
            'status' => 1,
        ]);

        $response
            ->assertStatus(404)
            ->assertJson([
                'status'=>'error',
                'message'=>'Product not found'
            ]);
    }

    public function testDeleteProductSuccess(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->delete('/api/admin/products/1');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Product deleted'
            ]);
    }

    public function testDeleteProductFail(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->delete('/api/admin/products/0');

        $response
            ->assertStatus(404)
            ->assertJson([
                'status'=>'error',
                'message'=> 'Product not found'
            ]);
    }
}
