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
use Illuminate\Support\Facades\Mail;

use Spatie\Permission\Models\Role;

use Livewire\Livewire;
use App\Livewire\SendEmail;
use App\Mail\Contact;

class AdminTest extends TestCase
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

    public function test_Adminユーザだけがダッシュボードへアクセスできる()
    {
        // 管理者のアクセス
        $adminUser = User::where('email', 'adminuser@testuser.com')->first();
        $this->actingAs($adminUser);
        $response = $this->get(route('admin.index'));
        $response->assertStatus(200);

        //一般ユーザのアクセス
        $generalUser = User::where('email', '!=', 'adminuser@testuser.com')->first();
        $this->actingAs($generalUser);
        $response = $this->get(route('admin.index'));
        $response->assertStatus(403);
    }

    public function test_ユーザーを削除()
    {
        // テストデータの準備
        $targetUser = User::where('email', '!=', 'adminuser@testuser.com')->first();

        //ユーザの削除
        $adminUser = User::where('email', 'adminuser@testuser.com')->first();
        $this->actingAs($adminUser);

        $response = $this->post(route('user.destroy', ['user_id' => $targetUser->id]));

        // レスポンスの検証
        $response->assertSessionHas('success', 'アカウントを削除しました');
        $response->assertRedirect(route('admin.index'));

        $this->assertSoftDeleted($targetUser);
        $this->assertDatabaseMissing('items', ['user_id' => $targetUser->id]);
        $this->assertDatabaseMissing('favorites', ['user_id' => $targetUser->id]);
        $this->assertDatabaseMissing('comments', ['user_id' => $targetUser->id]);
        $this->assertDatabaseMissing('user_profiles', ['user_id' => $targetUser->id]);
    }

    public function test_コメント削除()
    {
        $adminUser = User::where('email', 'adminuser@testuser.com')->first();
        $targetUser = User::factory()->create();

        $item = Item::factory()->create(['user_id' => $targetUser->id]);
        $comment = Comment::create([
            'user_id' => $targetUser->id,
            'item_id' => $item->id,
            'comment' => '他の人のコメント'
        ]);

        $this->actingAs($adminUser);
        $response = $this->post(route('comment.destroy', ['comment_id' => $comment->id]));
        $response->assertRedirect(route('item.detail', ['item_id' => $item->id]));
        $response->assertSessionHas('success', 'コメントを削除しました。');
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_メールタイトルが空()
    {
        Mail::fake();

        $targetUser = User::inRandomOrder()->first();
        $adminUser = User::where('email', 'adminuser@testuser.com')->first();

        $this->actingAs($adminUser);

        Livewire::test(SendEmail::class)
            ->set('subject', '')
            ->set('content', 'メール送信')
            ->set('email', $targetUser->email)
            ->call('send')
            ->assertHasErrors(['subject' => 'required']);
    }

    public function test_メール内容が空()
    {
        Mail::fake();

        $targetUser = User::inRandomOrder()->first();
        $adminUser = User::where('email', 'adminuser@testuser.com')->first();

        $this->actingAs($adminUser);

        Livewire::test(SendEmail::class)
            ->set('subject', 'タイトル')
            ->set('content', '')
            ->set('email', $targetUser->email)
            ->call('send')
            ->assertHasErrors(['content' => 'required']);
    }

    public function test_メール送信成功()
    {
        Mail::fake();

        $targetUser = User::inRandomOrder()->first();
        $adminUser = User::where('email', 'adminuser@testuser.com')->first();

        $this->actingAs($adminUser);

        Livewire::test(SendEmail::class)
        ->set('subject', 'タイトル')
        ->set('content', 'メール送信')
        ->set('email', $targetUser->email)
        ->call('send');

        Mail::assertSent(Contact::class, function ($mail)  use($targetUser)
        {
            return $mail->hasTo($targetUser->email) && $mail->subject === 'タイトル';
        });
    }
}