<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\ProductSeeder;
use Tests\TestCase;

class UserProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public static function successfulDisplayingProductProvider(): array
    {
        /* Numbers chosen by range that allowed in seeders of DB */
        return [
            'set_one'   => ['15'],
            'set_two'   => ['25'],
            'set_three' => ['5'],
        ];
    }

    public static function failedDisplayingProductProvider(): array
    {
        /* Numbers chosen by being more than limits in seeders of DB */
        return [
            'set_one'   => ['85'],
            'set_two'   => ['90'],
            'set_three' => ['55'],
        ];
    }

    #[DataProvider('successfulDisplayingProductProvider')]
    public function testSuccessfulResponseFromProduct($id): void
    {
        $this->seed(ProductSeeder::class);
        $response = $this->post('/api/product/'.$id);

        $response
            ->assertStatus(200)
            ->assertJson(['status'=>'success']);
    }

    #[DataProvider('failedDisplayingProductProvider')]
    public function testFailedResponseFromProduct($id): void
    {
        $this->seed(ProductSeeder::class);
        $response = $this->post('/api/product/'.$id);

        $response
            ->assertStatus(404)
            ->assertJson(['status'=>'error','message' => 'Product not found']);
    }

}
