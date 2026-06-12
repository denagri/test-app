<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Payment;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $mockSession = (object)['url' => 'https://stripe.com'];
        $mockSessions = \Mockery::mock();
        $mockSessions->shouldReceive('create')->andReturn($mockSession);
        $mockClient = \Mockery::mock('Stripe\StripeClient');
        $mockClient->checkout = (object)['sessions' => $mockSessions];
        $this->app->instance('Stripe\StripeClient', $mockClient);
    }

    public function test_user_can_complete_purchase(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $payment = Payment::create(['method' => 'カード払い']);
        $response = $this->actingAs($user)
            ->post(route('purchase.store', ['item_id' => $product->id]), [
                'payment_id' => $payment->id,
            ]);
        $response->assertStatus(302);
        $redirectUrl = $response->headers->get('Location');
        $this->assertStringContainsString('checkout.stripe.com', $redirectUrl);
        $this->assertDatabaseCount('purchases', 1);
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'payment' => $payment->id,
        ]);
    }

    public function test_purchased_product_displays_sold_on_index_page(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $payment = Payment::create(['method' => 'カード払い']);
        $addressId = DB::table('addresses')->insertGetId([
            'code' => '123-4567',
            'address' => '東京都新宿区',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'payment' => $payment->id,
            'address_id' => $addressId,
        ]);
        $response = $this->get(route('index'));
        $response->assertStatus(200);
        $response->assertSee('SOLD');
    }

    public function test_purchased_product_is_added_to_profile_purchase_list(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['name' => '購入した限定スニーカー']);
        $payment = Payment::create(['method' => 'カード払い']);
        $addressId = DB::table('addresses')->insertGetId([
            'code' => '123-4567',
            'address' => '東京都新宿区',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'payment' => $payment->id,
            'address_id' => $addressId,
        ]);
        $response = $this->actingAs($user)->get(route('mypage', ['tab' => 'buy']));
        $response->assertStatus(200);
        $response->assertSee('購入した限定スニーカー');
    }
    public function test_payment_methods_are_rendered_correctly_in_select_options(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $payment1 = Payment::create(['method' => 'コンビニ払い']);
        $payment2 = Payment::create(['method' => 'カード払い']);
        $response = $this->actingAs($user)
            ->get(route('purchase', ['item_id' => $product->id]));
        $response->assertStatus(200);
        $response->assertSee(sprintf('<option value="%d">%s</option>', $payment1->id, 'コンビニ払い'), false);
        $response->assertSee(sprintf('<option value="%d">%s</option>', $payment2->id, 'カード払い'), false);
    }
    public function test_updated_address_is_reflected_on_purchase_page(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $newAddressId = DB::table('addresses')->insertGetId([
            'code' => '987-6543',
            'address' => '大阪府大阪市',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $sessionKey = 'shipping_address_' . $product->id;

        $response = $this->actingAs($user)
            ->withSession([$sessionKey => $newAddressId])
            ->get(route('purchase', ['item_id' => $product->id]));

        $response->assertStatus(200);
        $response->assertSee('987-6543');
        $response->assertSee('大阪府大阪市');
    }

    public function test_purchased_product_is_linked_with_correct_shipping_address(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $payment = Payment::create(['method' => 'カード払い']);

        $newAddressId = DB::table('addresses')->insertGetId([
            'code' => '111-2222',
            'address' => '北海道札幌市',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $sessionKey = 'shipping_address_' . $product->id;

        $response = $this->actingAs($user)
            ->withSession([$sessionKey => $newAddressId])
            ->post(route('purchase.store', ['item_id' => $product->id]), [
                'payment_id' => $payment->id,
            ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'payment' => $payment->id,
            'address_id' => $newAddressId,
        ]);
    }
}