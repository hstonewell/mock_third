<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();
        $user = User::factory()->create([
            'email' => 'test@testuser.com',
            'password' => bcrypt('Password-1234')
        ]);
    }

    public function test_未入力または入力間違い()
    {
        $data = [
            'email' => 'wrongaddress@testuser.com',
            'password' => 'WrongPassword-1234'
        ];

        $response = $this->post(route('login', $data));
        $response->assertSessionHasErrors(['email' => 'ログインできません。入力した情報に誤りがないかご確認ください。']);
    }

    public function test_不正なメール形式()
    {
        $data = [
            'email' => 'invalid-email',
            'password' => 'Password-1234',
        ];

        $response = $this->post(route('login'), $data);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスは「ユーザー名@ドメイン」形式で入力してください',
        ]);
    }

    public function test_削除済みユーザのログイン()
    {
        $user = User::factory()->create([
            'email' => 'deleteduser@testuser.com',
            'password' => bcrypt('Password-1234'),
        ]);

        $user->delete();
        $data = [
            'email' => 'deleteduser@testuser.com',
            'password' => 'Password-1234'
        ];

        $response = $this->post(route('login'), $data);

        $response->assertSessionHasErrors([
            'email' => 'ログインできません。入力した情報に誤りがないかご確認ください。',
        ]);
    }

    public function test_入力が適切()
    {
        $data = [
            'email' => 'test@testuser.com',
            'password' => 'Password-1234'
        ];

        $response = $this->post(route('login'), $data);

        $response->assertRedirect(route('index'));
    }

    public function test_ログアウト()
    {
        $data = [
            'email' => 'test@testuser.com',
            'password' => 'Password-1234'
        ];

        $response = $this->post(route('login'), $data);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('index'));
    }
}
