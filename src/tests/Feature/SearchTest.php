<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setup();

        $user = User::factory(5)->create();
        $user = User::first();
        $this->actingAs($user);

        $otherUser = User::where('id', '!=', $user->id)->first();

        Item::create([
            'user_id' => $otherUser->id,
            'item_name' => 'Tシャツ',
            'price' => 1000,
            'image' => 'img/sample-image.png',
            'sold_out' => false,
        ]);

        Item::create([
            'user_id' => $otherUser->id,
            'item_name' => 'Yシャツ',
            'price' => 1000,
            'image' => 'img/sample-image.png',
            'sold_out' => false,
        ]);
    }

    public function test_空欄で検索()
    {
        $response = $this->get(route('search', ['keyword' => '']));
        $response->assertRedirect(route('index'));
        $this->assertEmpty(session('searchResults'));
    }

    public function test_検索結果なし()
    {
        $response = $this->get(route('search', ['keyword' => 'スカート']));
        $response->assertStatus(200);
        $response->assertSessionHas('fs_msg', 'スカートの検索結果');
        $response->assertSee('該当する商品が見つかりませんでした。');
        $searchResults = session('searchResults') ?? [];
        $this->assertEmpty($searchResults);
    }

    public function test_検索結果あり・部分一致()
    {
        $response = $this->get(route('search', ['keyword' => 'シャツ']));

        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertSessionHas('fs_msg', 'シャツの検索結果');

        $searchResults = session('searchResults');
        $this->assertNotEmpty($searchResults);
        $this->assertCount(2, $searchResults);

        $response->assertSee('シャツ', $searchResults->first()->item_name);
    }

    public function test_検索結果あり・完全一致()
    {
        $response = $this->get(route('search', ['keyword' => 'Tシャツ']));

        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertSessionHas('fs_msg', 'Tシャツの検索結果');

        $searchResults = session('searchResults');
        $this->assertNotEmpty($searchResults);
        $this->assertCount(1, $searchResults);

        $response->assertSee('Tシャツ', $searchResults->first()->item_name);
    }
}
