<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_view_profile_and_item_lists_after_login()
    {
        $condition = Condition::factory()->create();

        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        $soldProduct = Product::factory()->create([
            'sell_user_id' => $user->id,
            'name'        => '出品したテスト商品',
            'image_path'  => 'products/sample_sell.png',
            'price'       => 1000,
        ]);

        $otherUser = User::factory()->create();
        $boughtProduct = Product::factory()->create([
            'sell_user_id' => $otherUser->id,
            'name'        => '購入したテスト商品',
            'image_path'  => 'products/sample_buy.png',
            'price'       => 2000,
            'condition_id' => $condition->id,
        ]);
        
        Purchase::factory()->create([
            'user_id'    => $user->id,
            'product_id' => $boughtProduct->id,
            'payment'    => 'コンビニ払い',
            'address_id' => $user->address_id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('mypage', ['tab' => 'sell']));

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('出品した商品');
        $response->assertSee('出品したテスト商品');
        $response->assertDontSee('購入したテスト商品');

        $responseBuy = $this->actingAs($user)
            ->get(route('mypage', ['tab' => 'buy']));

        $responseBuy->assertStatus(200);
        $responseBuy->assertSee('購入した商品');
        $responseBuy->assertSee('購入したテスト商品');
        $responseBuy->assertDontSee('出品したテスト商品');
    }
    public function test_user_can_see_default_profile_values_in_edit_page()
    {
        $address = \App\Models\Address::factory()->create([
            'code'     => '123-4567',
            'address'  => '東京都渋谷区道玄坂1-2-3',
            'building' => 'コーポ井高101号',
        ]);

        $user = User::factory()->create([
            'name' => '初期値のユーザー名',
            'address_id' => $address->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('profile.update'));

        $response->assertStatus(200);
        $response->assertSee('初期値のユーザー名');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区道玄坂1-2-3');
        $response->assertSee('コーポ井高101号');
        $response->assertSee('profile.png');
    }
}