<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CartTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    private $user, $product, $count, $cart;

    const Table = 'carts';
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
        $this->cart = $this->createCart();
    }

    public function testAddToCart()
    {
        $response = $this->json('POST', route('cart.addToCart'), ['product_id' => $this->product->id]);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonCount($this->count)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['status', 'data', 'message']);
            })
            ->assertJsonFragment(['message' => 'Cart fetched successfully']);

        // $payload = json_decode($response->getContent());
        $this->assertDatabaseHas(self::Table, ['product_id' => $this->product->id, 'user_id' => $this->user->id]);
    }

    public function testGetCartItems()
    {
        $response = $this->json('GET', route('cart.getCartItems', []), []);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($this->count)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['status', 'data', 'message']);
            })
            ->assertJsonFragment(['message' => 'Cart fetched successfully']);

    }

    public function testDeleteCartItems()
    {

        $response = $this->json('DELETE', route('cart.deleteCartItems'), ['product_id' => $this->product->id]);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($this->count)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['status', 'data', 'message']);
            })
            ->assertJsonFragment(['message' => 'Item removed from cart successfully']);
        $this->assertSoftDeleted($this->cart);

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

    private function createCart()
    {
        return Cart::create(
            [
                'product_id' => $this->product->id,
                'user_id' => $this->user->id,
            ]
        );
    }
}
