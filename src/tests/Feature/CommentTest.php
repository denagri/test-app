<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_send_comment(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $response = $this->actingAs($user)
            ->post(route('comment.store', ['item_id' => $product->id]), [
                'content' => '購入を検討しています。',
            ]);
        $response->assertStatus(302);
        $this->assertDatabaseCount('comments', 1);
        $this->assertDatabaseHas('comments', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'content' => '購入を検討しています。',
        ]);
        $detailResponse = $this->get(route('items.show', ['item_id' => $product->id]));
        $detailResponse->assertSee('購入を検討しています。');
    }

    public function test_guest_user_cannot_send_comment(): void
    {
        $product = Product::factory()->create();
        $response = $this->post(route('comment.store', ['item_id' => $product->id]), [
            'content' => 'ゲストからのコメントです。',
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_comment_validation_fails_if_content_is_empty(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $response = $this->actingAs($user)
            ->post(route('comment.store', ['item_id' => $product->id]), [
                'content' => '',
            ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['content']);
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_comment_validation_fails_if_content_exceeds_255_characters(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $longComment = str_repeat('あ', 255);
        $response = $this->actingAs($user)
            ->post(route('comment.store', ['item_id' => $product->id]), [
                'content' => $longComment,
            ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['content']);
        $this->assertDatabaseCount('comments', 0);
    }
}