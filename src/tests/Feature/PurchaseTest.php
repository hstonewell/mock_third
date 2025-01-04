<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;

use Mockery;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();

        User::factory(5)->create();

        $user = User::first();
        $this->actingAs($user);

        $otherUser = User::where('id', '!=', $user->id)->first();

        Item::create([
            'user_id' => $otherUser->id,
            'item_name' => 'テスト用アイテム',
            'price' => 1000,
            'image' => 'img/sample-image.png',
            'sold_out' => fake()->boolean(),
        ]);
    }

    public function test_カードで購入()
    {
        $item = Item::first();

        $response = $this->post(route('payment.create', ['item_id' => $item->id]), [
            'payment_method' => 'クレジットカード',
            'name' => $item->item_name,
            'price' => $item->price,
        ]);

        $response->assertRedirectContains('https://checkout.stripe.com');
    }

    public function test_銀行振込で購入()
    {
        $item = Item::first();

        $mockCustomer = Mockery::mock(\Stripe\Customer::class);
        $mockCustomer->shouldReceive('create')->andReturn((object)['id' => 'cus_test123']);

        $mockPaymentIntent = Mockery::mock(\Stripe\PaymentIntent::class);
        $mockPaymentIntent->shouldReceive('create')->andReturn((object)[
            'id' => 'pi_test123',
            'next_action' => (object)[
                'display_bank_transfer_instructions' => (object)[
                    'financial_addresses' => [
                        ['type' => 'zengin', 'zengin' => ['bank_code' => '0001', 'branch_code' => '123']]
                    ]
                ]
            ]
        ]);

        $response = $this->post(route('payment.create', ['item_id' => $item->id]), [
            'payment_method' => '銀行振込',
            'name' => $item->item_name,
            'price' => $item->price,
        ]);

        $response->assertRedirect(route('bank.show', ['item_id' => $item->id]));
        $this->assertDatabaseHas('purchased_items', [
            'item_id' => $item->id,
            'status' => '1',
            'payment_method' => '銀行振込',
        ]);
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'sold_out' => true
        ]);
    }

    public function test_コンビニ支払で購入()
    {
        $item = Item::first();

        $mockCustomer = Mockery::mock(\Stripe\Customer::class);
        $mockCustomer->shouldReceive('create')->andReturn((object)['id' => 'cus_test123']);

        $mockPaymentIntent = Mockery::mock(\Stripe\PaymentIntent::class);
        $mockPaymentIntent->shouldReceive('create')->andReturn((object)[
            'id' => 'pi_test123',
        ]);

        $response = $this->post(route('payment.create', ['item_id' => $item->id]), [
            'payment_method' => 'コンビニ支払',
            'name' => $item->item_name,
            'price' => $item->price,
        ]);

        $response->assertRedirect(route('konbini.show', ['item_id' => $item->id]));
        $this->assertDatabaseHas('purchased_items', [
            'item_id' => $item->id,
            'status' => '1',
            'payment_method' => 'コンビニ支払',
        ]);
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'sold_out' => true
        ]);
    }
}
