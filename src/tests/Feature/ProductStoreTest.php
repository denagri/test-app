<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductStoreTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_can_create_product_successfully(): void
    {
        $category1 = Category::factory()->create(['kind' => 'レディース']);
        $category2 = Category::factory()->create(['kind' => 'トップス']);
        $condition = Condition::factory()->create(['kind' => '新品、未使用']);
        $user = User::factory()->create();
        $dummyImage = UploadedFile::fake()->create('product_tshirt.png', 0, 'image/png');

        $response = $this->actingAs($user)
            ->post(route('listing.store'), [
                'image'        => $dummyImage,
                'category_ids' => [$category1->id, $category2->id],
                'condition_id' => $condition->id,
                'name'         => '【新品】ロゴTシャツ ホワイト Mサイズ',
                'brand'        => 'ブランドA',
                'explanation'  => "今季モデルのTシャツです。\n試着のみ。",
                'price'        => 3000,
            ]);

        $response->assertRedirect(route('index'));

        $this->assertDatabaseHas('products', [
            'sell_user_id' => $user->id,
            'condition_id' => $condition->id,
            'name'         => '【新品】ロゴTシャツ ホワイト Mサイズ',
            'brand'        => 'ブランドA',
            'explanation'  => "今季モデルのTシャツです。\n試着のみ。",
            'price'        => 3000,
        ]);

        $this->assertDatabaseHas('category_product', [
            'product_id'  => 1,
            'category_id' => $category1->id,
        ]);
        $this->assertDatabaseHas('category_product', [
            'product_id'  => 1,
            'category_id' => $category2->id,
        ]);

        $mypageResponse = $this->actingAs($user)->get('/mypage?tab=sell');
        $mypageResponse->assertSee('【新品】ロゴTシャツ ホワイト Mサイズ');
    }
}