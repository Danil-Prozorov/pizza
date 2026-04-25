<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\OrderStatusesSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class AdminOrdersControllerTest extends TestCase
{
    use RefreshDatabase;

    public static function updateOrderParamsSuccessProvider(): array
    {
        return [
            'set_one' => [1,2,3,'address test2'],
            'set_two' => [1,3,2,'address test3'],
            'set_three' => [1,4,1,'address test4'],
            'set_four' => [1,5,3,'address test5'],
        ];
    }

    public static function updateOrderProductsParamsSuccessProvider(): array
    {
        return [
            'set_one'   => [1, 1,'test name', 'img/testimg1.jpg', 'test short description', 120, 3],
            'set_two'   => [1,1,'test name2','img/testimg2.jpg','test short description',120,2],
            'set_three' => [1,1,'test name3','img/testimg3.jpg','test short description',120,1],
            'set_four'  => [1,1,'test name4','img/testimg4.jpg','test short description',150,1],
        ];
    }

    public static function updateOrderProductsParamsFailProvider(): array
    {
        return [
            'set_one'   => [0, 29,'test name', 'img/testimg1.jpg', 'test short description', 120, 3],
            'set_two'   => [1,29,'test name2','img/testimg2.jpg','test short description',1,2],
            'set_three' => [150,1,'test name3','img/testimg3.jpg','test short description',120,999],
            'set_four'  => [1,0,'test name4','img/testimg4.jpg','test short description',150,999],
        ];
    }

    public static function deleteOrderIdsFailProvider(): array
    {
        return [
            'set_one' => [0],
            'set_two' => [100],
            'set_three' => ['test'],
        ];
    }

    public function testViewOrders(): void
    {
        $this->seed(UserSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$user['token']])->post('/api/admin/orders');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => $response['data']
            ]);
    }

    public function testCreateOrderSuccess(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$user['token']])->post('/api/admin/orders/create',['user_id' => 1,'address' => 'test address','products_id' => [1],'quantity' => [1]]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Order created successfully.'
            ]);
    }

    public function testCreateOrderFail(): void
    {
        $this->seed(UserSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$user['token']])->post('/api/admin/orders/create',['user_id' => 129,'address' => 'test address','products_id' => [20],'quantity' => [150]]);

        $response
            ->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'Cannot create order.'
            ]);

    }

    public function testShowOrder(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/orders/create',['user_id' => 1,'address' => 'test address','products_id' => [1],'quantity' => [1]]);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/orders/1');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => $response['data']
            ]);
    }

    public function testShowOrderFail(): void
    {
        $this->seed(UserSeeder::class);
        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/orders/create',['user_id' => 1,'address' => 'test address','products_id' => [1],'quantity' => [1]]);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/orders/1');

        $response
            ->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'Cannot show order.'
            ]);
    }

    public function testDeleteOrderSuccess(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/orders/create',['user_id' => 1,'address' => 'test address','products_id' => [1],'quantity' => [1]]);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->delete('/api/admin/orders/1');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Order and related products deleted successfully.'
            ]);
    }

    #[DataProvider('deleteOrderIdsFailProvider')]
    public function testDeleteOrderFail($id): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/orders/create',['user_id' => 1,'address' => 'test address','products_id' => [1],'quantity' => [1]]);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->delete('/api/admin/orders/'.$id);

        $response
            ->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'Cannot delete order.'
            ]);
    }

    #[DataProvider('updateOrderParamsSuccessProvider')]
    public function testUpdateOrder($user_id,$status,$products,$address): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);
        $this->seed(OrderStatusesSeeder::class);

        $user  = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);

        $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/orders/create',[
            'user_id' => 1,
            'address' => 'test address',
            'products_id' => [1],
            'quantity' => [1]
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->put('/api/admin/orders/1',[
            'user_id'  => $user_id,
            'status'   => $status,
            'products' => $products,
            'address'  => $address
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Order updated successfully.'
            ]);

    }

    public function testUpdateOrderFails()
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);
        $this->seed(OrderStatusesSeeder::class);

        $user  = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);

        $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/orders/create',[
            'user_id' => 1,
            'address' => 'test address',
            'products_id' => [1],
            'quantity' => [1]
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->put('/api/admin/orders/0',[
            'user_id'  => 1,
            'status'   => 1,
            'products' => 1,
            'address'  => 'test'
        ]);

        $response->assertStatus(404);
    }

    #[DataProvider('updateOrderProductsParamsSuccessProvider')]
    public function testUpdateOrderProducts($order_id,$product,$name,$image,$desc,$price,$amount): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);
        $this->seed(OrderStatusesSeeder::class);

        $user  = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);

        $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/orders/create',[
            'user_id' => 1,
            'address' => 'test address',
            'products_id' => [1],
            'quantity' => [1]
        ]);

        $response = $this->withHeaders(['Authorization'=>'Bearer '.$user['token']])->put('/api/admin/orders/product',[
            'order_id'          => $order_id,
            'product_id'        => $product,
            'name'              => $name,
            'image'             => $image,
            'short_description' => $desc,
            'price'             => $price,
            'product_amount'    => $amount,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Product updated successfully.'
            ]);
    }

    #[DataProvider('updateOrderProductsParamsFailProvider')]
    public function testUpdateOrderProductsFail($order_id,$product,$name,$image,$desc,$price,$amount): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);
        $this->seed(OrderStatusesSeeder::class);

        $user  = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);

        $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/orders/create',[
            'user_id' => 1,
            'address' => 'test address',
            'products_id' => [1],
            'quantity' => [1]
        ]);

        $response = $this->withHeaders(['Authorization'=>'Bearer '.$user['token']])->put('/api/admin/orders/product',[
            'order_id'          => $order_id,
            'product_id'        => $product,
            'name'              => $name,
            'image'             => $image,
            'short_description' => $desc,
            'price'             => $price,
            'product_amount'    => $amount,
        ]);

        $response
            ->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'Cannot update products.'
            ]);
    }
}
