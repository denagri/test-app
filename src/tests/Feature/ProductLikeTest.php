<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductLikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_like_a_product(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $product = Product::factory()->create();
        $this->actingAs($user);
        $response = $this->post(route('like.toggle', ['product_id' => $product->id]));
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
        $detailResponse = $this->get(route('items.show', ['item_id' => $product->id]));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('1');
    }

    public function test_authenticated_user_can_unlike_a_product(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $product = Product::factory()->create();
        $product->likes()->create(['user_id' => $user->id]);
        $this->actingAs($user);
        $response = $this->post(route('like.toggle', ['product_id' => $product->id]));
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $detailResponse = $this->get(route('items.show', ['item_id' => $product->id]));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('0');
    }
}