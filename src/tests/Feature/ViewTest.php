<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\UserProfile;

use Spatie\Permission\Models\Role;

class ViewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setup();

        //管理ユーザの作成
        $adminRole = Role::create(['name' => 'admin']);

        $adminUser = User::create([
            'email' => 'adminuser@testuser.com',
            'password' => bcrypt('Admin-1234'),
        ]);

        UserProfile::factory()->create([
            'user_id' => $adminUser->id
        ]);

        $adminUser->assignRole($adminRole);

        //一般ユーザの作成・データ追加
        Role::create(['name' => 'user']);
        $generalRole = Role::where('name', 'user')->first();

        $generalUsers = User::factory(10)->create();
        $generalUsers->each(function ($generalUser) use ($generalRole) {
            // ユーザープロフィールを作成
            UserProfile::factory()->create([
                'user_id' => $generalUser->id,
            ]);

            // ロールを割り当て
            $generalUser->assignRole($generalRole);

            // ユーザーのアイテムを作成
            Item::factory(5)->create([
                'user_id' => $generalUser->id,
                'item_name' => 'テスト用アイテム',
                'price' => 1000,
                'image' => 'img/sample-image.png',
                'sold_out' => fake()->boolean(),
            ]);

            // 他ユーザーのアイテムをランダムに取得し、お気に入りを作成
            $otherItems = Item::where('user_id', '!=', $generalUser->id)
                ->inRandomOrder()
                ->limit(10)
                ->get();

            $otherItems->each(function ($item) use ($generalUser) {
                Comment::create([
                    'user_id' => $generalUser->id,
                    'item_id' => $item->id,
                    'comment' => 'test',
                ]);
                Favorite::create([
                    'user_id' => $generalUser->id,
                    'item_id' => $item->id,
                ]);
            });
        });
    }

    public function test_ログインせずにアクセス()
    {
        $item = Item::first();

        $pages = [
            ['url' => route('index'), 'status' => 200],
            ['url' => route('item.detail', ['item_id' => $item->id]), 'status' => 200],
            ['url' => route('register'), 'status' => 200],
            ['url' => route('login'), 'status' => 200],
            ['url' => route('purchase.show', ['item_id' => $item->id]), 'status' => 302],
            ['url' => route('sell.show'), 'status' => 302],
            ['url' => route('mypage.show'), 'status' => 302],
            ['url' => route('profile.show', ['item_id' => $item->id]), 'status' => 302],
            ['url' => route('address.show', ['item_id' => $item->id]), 'status' => 302],
            ['url' => route('admin.index'), 'status' => 302],
        ];

        foreach ($pages as $page) {
            $response = $this->get($page['url']);
            $response->assertStatus($page['status']);
        }
    }

    public function test_一般ユーザとしてアクセス()
    {
        $user = User::where('email', '!=', 'adminuser@testuser.com')->first();
        $this->actingAs($user);

        $item = Item::first();

        $pages = [
            ['url' => route('purchase.show', ['item_id' => $item->id]), 'status' => 200],
            ['url' => route('sell.show'), 'status' => 200],
            ['url' => route('mypage.show'), 'status' => 200],
            ['url' => route('profile.show', ['item_id' => $item->id]), 'status' => 200],
            ['url' => route('address.show', ['item_id' => $item->id]), 'status' => 200],
            ['url' => route('admin.index'), 'status' => 403],
        ];

        foreach ($pages as $page) {
            $response = $this->get($page['url']);
            $response->assertStatus($page['status']);
        }
    }

    public function test_管理者としてアクセス()
    {
        $adminUser = User::where('email', 'adminuser@testuser.com')->first();
        $this->actingAs($adminUser);

        $pages = [
            ['url' => route('admin.index'), 'status' => 200],
        ];

        foreach ($pages as $page) {
            $response = $this->get($page['url']);
            $response->assertStatus($page['status']);
        }
    }
}
