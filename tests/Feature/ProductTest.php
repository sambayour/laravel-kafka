<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    private $user, $product, $count;

    const Table = 'products';
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->createUser();
        $user = User::first();
        $this->actingAs($user);
        $this->user = $user;
        $this->count = 3;
        $this->product = $this->createProduct();
    }

    public function testIndex()
    {
        $response = $this->json('GET', route('product.index'), []);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($this->count)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['status', 'data', 'message']);
            })
            ->assertJsonFragment(['message' => 'Product fetched successfully']);

        $payload = json_decode($response->getContent());
        $this->assertEquals(1, count(($payload->data)));
    }

    public function testShow()
    {
        $response = $this->json('GET', route('product.show', ['id' => $this->product->id]), []);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($this->count)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['status', 'data', 'message']);
            })
            ->assertJsonFragment(['message' => 'Product fetched successfully']);

    }

    public function testCreate()
    {
        $data = [
            'name' => 'Shirt',
            'price' => 3000.3,
            'description' => $this->faker->text,
            'user_id' => $this->user->id,
            'img_path' => null,
        ];

        $response = $this->json('POST', route('product.store'), $data);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonCount($this->count)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['status', 'data', 'message']);
            })
            ->assertJsonFragment(['message' => 'Product created successfully']);
        $this->assertDatabaseHas(self::Table, ['name' => $data['name']]);

    }

    public function testUpdate()
    {
        $data = [
            'name' => 'Joggers',
        ];

        $response = $this->json('PATCH', route('product.update', ['id' => $this->product->id]), $data);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($this->count)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['status', 'data', 'message']);
            })
            ->assertJsonFragment(['message' => 'Product updated successfully']);
        $this->assertDatabaseHas(self::Table, ['name' => $data['name']]);

    }

    public function testDelete()
    {

        $response = $this->json('DELETE', route('product.destroy', ['id' => $this->product->id]), []);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($this->count)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['status', 'data', 'message']);
            })
            ->assertJsonFragment(['message' => 'Product deleted successfully']);
        $this->assertSoftDeleted($this->product);

    }

    private function createUser()
    {
        return User::create(
            [
                'first_name' => $this->faker->name,
                'last_name' => $this->faker->name,
                'email' => $this->faker->email,
                'password' => bcrypt('password@123'),
            ]
        );
    }

    private function createProduct()
    {
        return Product::create(
            [
                'name' => $this->faker->name,
                'price' => 4992.3,
                'description' => $this->faker->text,
                'user_id' => $this->user->id,
                'img_path' => null,
            ]
        );
    }
}
