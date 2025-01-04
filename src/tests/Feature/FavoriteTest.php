<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;

use Illuminate\Support\Facades\Auth;

class FavoriteTest extends TestCase
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

    public function test_お気に入り追加()
    {
        $item = Item::first();
        Favorite::favorite(Auth::id(), $item->id);

        $response = $this->post(route('favorite', ['item_id' => $item->id]));
        $response->assertRedirect(route('item.detail', ['item_id' => $item->id]));
    }

    public function test_お気に入り削除()
    {
        $item = Item::first();
        Favorite::favorite(Auth::id(), $item->id);
        Favorite::where('user_id', Auth::id())->where('item_id', $item->id)->delete();

        $response = $this->post(route('unfavorite', ['item_id' => $item->id]));
        $response->assertRedirect(route('item.detail', ['item_id' => $item->id]));
    }
}
