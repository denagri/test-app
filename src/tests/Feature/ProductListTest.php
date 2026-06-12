<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\PaymentsTableSeeder;

class ProductListTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(PaymentsTableSeeder::class);
    }

    public function test_can_get_all_products_when_guest():void
    {
        Product::factory()->count(3)->create();
        $response = $this->get(route('index'));
        $response->assertStatus(200);
        $response->assertViewHas('products',function($products){
            return $products->count() ===3;
        });
    }

    public function test_sold_products_display_sold_label():void
    {
        $activeProduct = Product::factory()->create(['name'=>'販売中の商品']);
        $soldProduct = Product::factory()->create(['name' =>'売り切れの商品']);
        $address = \App\Models\Address::factory()->create();
        DB::table('purchases')->insert([
            'product_id' => $soldProduct->id,
            'user_id' => User::factory()->create()->id,
            'payment' => 1,
            'address_id' => $address->id,
            'created_at' =>now(),
            'updated_at' =>now(),
        ]);
        $response = $this->get(route('index'));
        $response->assertStatus(200);
        $response->assertSee('SOLD');
    }

    public function test_own_products_are_not_displayed_in_list():void
    {
        $me =User::factory()->create();
        $otherUser = User::factory()->create();
        $myProduct = Product::factory()->create([
            'name' => '私の出品した服',
            'sell_user_id' => $me->id
        ]);
        $otherProduct = Product::factory()->create([
            'name' =>'他人の出品した本',
            'sell_user_id' => $otherUser->id
        ]);
        $response = $this->actingAs($me)->get(route('index'));
        $response->assertStatus(200);
        $response->assertSee('他人の出品した本');
        $response->assertDontSee('私の出品した服');
    }
    public function test_guest_cannot_see_any_products_on_mylist(): void
    {
        Product::factory()->count(3)->create();
        $response = $this->get(route('index', ['tab' => 'mylist']));

        $response->assertStatus(200);
        $response->assertViewHas('products', function ($products) {
            return $products->isEmpty();
        });
    }
    public function test_logged_in_user_can_see_liked_products_on_mylist(): void
    {
        $user = User::factory()->create();
        $likedProduct = Product::factory()->create(['name' => 'いいねした服']);
        $unlikedProduct = Product::factory()->create(['name' => 'いいねしていない本']);
        \App\Models\Like::create([
            'user_id' => $user->id,
            'product_id' => $likedProduct->id,
        ]);
        $response = $this->actingAs($user)->get(route('index', ['tab' => 'mylist']));

        $response->assertStatus(200);
        $response->assertViewHas('products', function ($products) use ($likedProduct, $unlikedProduct) {
            return $products->contains('id', $likedProduct->id) && !$products->contains('id', $unlikedProduct->id);
        });
    }
    public function test_sold_products_display_sold_label_on_mylist(): void
    {
        $user = User::factory()->create();
        $address = \App\Models\Address::factory()->create();
        $soldProduct = Product::factory()->create(['name' => '売り切れた商品']);
        $activeProduct = Product::factory()->create(['name' => '販売中の商品']);
        \App\Models\Like::create(['user_id' => $user->id, 'product_id' => $soldProduct->id]);
        \App\Models\Like::create(['user_id' => $user->id, 'product_id' => $activeProduct->id]);
        DB::table('purchases')->insert([
            'product_id' => $soldProduct->id,
            'user_id' => User::factory()->create()->id,
            'payment' => 1,
            'address_id' => $address->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $response = $this->actingAs($user)->get(route('index', ['tab' => 'mylist']));
        $response->assertStatus(200);
        $response->assertViewHas('products', function ($products) use ($soldProduct, $activeProduct) {
            $soldItem = $products->firstWhere('id', $soldProduct->id);
            $activeItem = $products->firstWhere('id', $activeProduct->id);
            return $soldItem && $soldItem->purchase !== null && $activeItem && $activeItem->purchase === null;
        });
    }
    public function test_searching_by_keyword_displays_partially_matching_products(): void
    {
        $otherUser = User::factory()->create();
        $matchProduct = Product::factory()->create(['name' => '限定デザインのスニーカー', 'sell_user_id' => $otherUser->id]);
        $unmatchProduct = Product::factory()->create(['name' => '普通のサンダル', 'sell_user_id' => $otherUser->id]);
        $response = $this->get(route('index', ['keyword' => 'スニーカー']));
        $response->assertStatus(200);
        $response->assertViewHas('products', function ($products) use ($matchProduct, $unmatchProduct) {
            return $products->contains('id', $matchProduct->id) && !$products->contains('id', $unmatchProduct->id);
        });
    }
    public function test_search_keyword_is_retained_after_switching_to_mylist_tab(): void
    {
        $user = User::factory()->create();
        $searchResponse = $this->get(route('index', ['keyword' => 'ジャケット']));
        $searchResponse->assertStatus(200);
        $this->assertEquals('ジャケット', $searchResponse->viewData('keyword'));
        $mylistResponse = $this->actingAs($user)->get(route('index', [
            'tab' => 'mylist',
            'keyword' => 'ジャケット'
        ]));
        $mylistResponse->assertStatus(200);
        $this->assertEquals('ジャケット', $mylistResponse->viewData('keyword'));
        $this->assertEquals('mylist', $mylistResponse->viewData('tab'));
    }
}
