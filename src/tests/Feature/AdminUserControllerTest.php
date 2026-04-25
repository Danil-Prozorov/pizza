<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class AdminUserControllerTest extends TestCase
{

    use RefreshDatabase;

    public function testGetUsersList(): void
    {
        $this->seed(UserSeeder::class);

        $user     = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/users');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'users_list' => $response['users_list']
            ]);
    }

    public function testCreateUserSuccess(): void
    {
        $this->seed(UserSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/users/create',[
            'username' => 'second test user',
            'email'    => 'mail@gmail.com',
            'password' => 'el passwordini',
            'is_admin' => 0
        ]);

        $response->assertStatus(200)->assertJson([
            'status' => 'success',
            'message' => 'User created'
        ]);
    }

    public function testCreateUserFail(): void
    {
        $this->seed(UserSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/users/create',[
            'username' => 1,
            'email'    => 'mail@gmail.com',
            'password' => 'el passwordini',
            'is_admin' => 0
        ]);

        $response
            ->assertStatus(302);
    }

    public function testUserShowSuccess(): void
    {
        $this->seed(UserSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/users/1');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'user_data' => $response['user_data']
            ]);
    }

    public function testUserShowFail(): void
    {
        $this->seed(UserSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->post('/api/admin/users/0');

        $response
            ->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'User not found'
            ]);
    }

    public function testUserUpdateSuccess(): void
    {
        $this->seed(UserSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->put('/api/admin/users/1',[
            'username' => 'testyfying',
            'password' => 'testyfying',
            'email' => 'tmail@gmail.com',
            'is_admin' => 0,
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'User updated'
            ]);
    }

    public function testUserUpdateFail(): void
    {
        $this->seed(UserSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->put('/api/admin/users/1',[
            'password' => 'password',
            'email' => 'tests@gmail.com',
            'is_admin' => 0,
        ]);

        $response
            ->assertStatus(202)
            ->assertJson([
                'status' => 'error',
                'message' => 'User not updated'
            ]);
    }

    public function testDeleteUserSuccess(): void
    {
        $this->seed(UserSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->delete('/api/admin/users/1');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'User deleted'
            ]);
    }

    public function testDeleteUserFail(): void
    {
        $this->seed(UserSeeder::class);

        $user = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$user['token']])->delete('/api/admin/users/0');

        $response
            ->assertStatus(404)
            ->assertJson([
                'status' => 'error' ,
                'message' => 'Impossible to delete user'
            ]);
    }
}
