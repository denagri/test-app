<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDetailDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_detail_page_displays_all_basic_information(): void
    {
        $condition = Condition::factory()->create(['kind' => '新品、未使用']);
        $product = Product::factory()->create([
            'name' => 'テスト商品A',
            'brand' => 'サンプルブランド',
            'price' => 2980,
            'explanation' => 'これは商品の説明文です。',
            'condition_id' => $condition->id,
        ]);
        $response = $this->get(route('items.show', ['item_id' => $product->id]));
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->brand);
        $response->assertSee(number_format($product->price));
        $response->assertSee($product->explanation);
        $response->assertSee($condition->kind);
    }

    public function test_product_detail_page_displays_multiple_categories(): void
    {
        $product = Product::factory()->create();
        $category1 = Category::factory()->create(['kind' => 'レディース']);
        $category2 = Category::factory()->create(['kind' => 'トップス']);
        if (method_exists($product, 'categories')) {
            $product->categories()->attach([$category1->id, $category2->id]);
        }
        $response = $this->get(route('items.show', ['item_id' => $product->id]));
        $response->assertStatus(200);
        $response->assertSee($category1->kind);
        $response->assertSee($category2->kind);
    }

    public function test_product_detail_page_displays_comments_with_user_info(): void
    {
        $product = Product::factory()->create();
        $user = User::factory()->create(['name' => 'テスト太郎']);
        Comment::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'content' => 'この商品について質問があります！',
        ]);
        $response = $this->get(route('items.show', ['item_id' => $product->id]));
        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee('この商品について質問があります！');
    }
}