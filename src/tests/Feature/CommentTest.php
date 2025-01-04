<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

use Illuminate\Support\Facades\Auth;

class CommentTest extends TestCase
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

    public function test_コメントする()
    {
        $item = Item::first();

        $data = [
            'comment' => 'コメントのテスト',
        ];

        $response = $this->post(route('comment.create', ['item_id' => $item->id]), $data);
        $response->assertRedirect(route('item.detail', ['item_id' => $item->id]));
    }

    public function test_コメントが空の状態で送信ボタンを押す()
    {
        $item = Item::first();

        $data = [
            'comment' => '',
        ];

        $response = $this->post(route('comment.create', ['item_id' => $item->id]), $data);
        $response->assertSessionHasErrors(['comment' => 'コメントを入力してください']);
    }

    public function test_コメントが長すぎる()
    {
        $item = Item::first();

        $data = [
            'comment' => str_repeat('a', 1001),
        ];

        $response = $this->post(route('comment.create', ['item_id' => $item->id]), $data);
        $response->assertSessionHasErrors(['comment' => 'コメントは、1000文字以下で指定してください。']);
    }

    public function test_自分のコメントを削除する()
    {
        $item = Item::first();

        $data = [
            'comment' => 'コメントのテスト',
        ];

        $response = $this->post(route('comment.create', ['item_id' => $item->id]), $data);

        $comment = Comment::where('item_id', $item->id)->where('user_id', Auth::id())->first();
        $comment->delete();

        $response->assertRedirect(route('item.detail', ['item_id' => $item->id]));
    }
}
