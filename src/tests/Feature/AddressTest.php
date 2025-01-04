<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;

class AddressTest extends TestCase
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

    public function test_郵便番号が空()
    {
        $item = Item::first();

        $data = [
            'postcode' => '',
            'address' => '東京都◯◯区××12-3',
            'building' => 'テストマンション101'
        ];

        $response = $this->post(route('address.create', ['item_id' => $item->id], $data));
        $response->assertSessionHasErrors(['postcode' => '郵便番号を入力してください']);
    }

    public function test_郵便番号の桁数が不正()
    {
        $item = Item::first();

        $data = [
            'postcode' => '12345',
            'address' => '東京都◯◯区××12-3',
            'building' => 'テストマンション101',
        ];

        $response = $this->post(route('address.create', ['item_id' => $item->id]), $data);

        $response->assertSessionHasErrors(['postcode' => '郵便番号は7桁の半角数字で入力してください']);
    }


    public function test_郵便番号に数字以外が含まれる()
    {
        $item = Item::first();

        $data = [
            'postcode' => '1234abc',
            'address' => '東京都◯◯区××12-3',
            'building' => 'テストマンション101',
        ];

        $response = $this->post(route('address.create', ['item_id' => $item->id]), $data);
        $response->assertSessionHasErrors(['postcode' => '郵便番号は7桁の半角数字で入力してください']);
    }

    public function test_住所が空()
    {
        $item = Item::first();

        $data = [
            'postcode' => '1234567',
            'address' => '',
            'building' => 'テストマンション101'
        ];

        $response = $this->post(route('address.create', ['item_id' => $item->id]), $data);
        $response->assertSessionHasErrors(['address' => '住所を入力してください']);
    }

    public function test_住所が長すぎる()
    {
        $item = Item::first();

        $data = [
            'postcode' => '1234567',
            'address' => str_repeat('a', 192),
            'building' => 'テストマンション101'
        ];

        $response = $this->post(route('address.create', ['item_id' => $item->id]), $data);
        $response->assertSessionHasErrors(['address' => '住所は、191文字以下で指定してください。']);
    }

    public function test_建物名が空()
    {
        $item = Item::first();

        $data = [
            'postcode' => '1234567',
            'address' => '東京都◯◯区××12-3',
            'building' => ''
        ];

        $response = $this->post(route('address.create', ['item_id' => $item->id]), $data);
        $response->assertRedirect(route('purchase.show', ['item_id' => $item->id]));
    }

    public function test_建物名が長すぎる()
    {
        $item = Item::first();

        $data = [
            'postcode' => '1234567',
            'address' => '東京都◯◯区××12-3',
            'building' => str_repeat('a', 192),
        ];

        $response = $this->post(route('address.create', ['item_id' => $item->id]), $data);
        $response->assertSessionHasErrors(['building' => '建物名は、191文字以下で指定してください。']);
    }
}
