<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\UserSeeder;
use App\Models\User;
use Tests\TestCase;

class UserAuthControllerTest extends TestCase
{
    use RefreshDatabase;


    public static function invalidLoginCredentialsProvider(): array
    {
        return [
            'set_one'   => ['email' => 'g@gmail.com', 'password' => 'jdsfsdfkcvf;'],
            'set_two'   => ['email' => '913kas@g.r', 'password' => 'lknjdsafsd'],
            'set_three' => ['email' => 'dsfsdf', 'password' => 'dsfsdfkcvf;'],
            'set_four'  => ['email' => 21475, 'password' =>'dsfswe3fs%4'],
            'set_five'  => ['email' => 'sdfsad ', 'password' =>24215]
        ];
    }

    public static function invalidRegistrationParamsProvider(): array
    {
        return [
            'set_one'   => ['username' => '', 'email' => 'test@gmail.com', 'password' => 'password'],
            'set_two'   => ['username' => 'test user2', 'email' => '', 'password' => 'password'],
            'set_three' => ['username' => 'test user3', 'email' => '', 'password' => ''],
            'set_four'  => ['username' => 'test user4', 'email' => '', 'password' => ''],
            'set_five'  => ['username' => 'test user', 'email' => 'tests@gmail.com', 'password' => 'password'],
        ];
    }

    public function testUserRegistrationSuccess(): void
    {
        $this->seed(UserSeeder::class);
        $response = $this->post('/api/register',['username' => 'test user', 'email' => 'test@gmail.com', 'password' => 'password']);

        $this
            ->assertIsArray($response['user']);
    }

    public function testUserLoginGettingTokenSuccess(): void
    {
        $this->seed(UserSeeder::class);
        $response = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);

        $this
            ->assertIsString($response['token']);
    }

    public function testUserLogout(): void
    {
        $this->seed(UserSeeder::class);
        $token    = $this->post('/api/login',['email' => 'tests@gmail.com','password' => 'password']);
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$token['token']])->post('/api/logout');

        $response
            ->assertStatus(200);
    }

    #[DataProvider('invalidRegistrationParamsProvider')]
    public function testUserRegisterFail($username,$email,$password): void
    {
        $this->seed(UserSeeder::class);
        $response = $this->post('/api/register',['username' => $username, 'email' => $email, 'password' => $password]);

        $response
            ->assertStatus(302);
    }

    #[DataProvider('invalidLoginCredentialsProvider')]
    public function testUserLoginFail($email,$password): void
    {
        $this->seed(UserSeeder::class);
        $response = $this->post('/api/login',['email' => $email,'password' => $password]);

        $response
            ->assertStatus(401)
            ->assertJson(['error' => 'Invalid credentials']);
    }

}
