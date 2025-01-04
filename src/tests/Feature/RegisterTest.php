<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_メールアドレス未入力()
    {
        $data = [
            'email' => '',
            'password' => 'Password-1234'
        ];
        $response = $this->post(route('register', $data));
        $response->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals(
            'メールアドレスを入力してください',
            $errors->first('email')
        );
    }

    public function test_パスワード未入力()
    {
        $data = [
            'email' => 'test@testuser.com',
            'password' => ''
        ];
        $response = $this->post(route('register', $data));
        $response->assertSessionHasErrors('password');

        $errors = session('errors');
        $this->assertEquals(
            'パスワードを入力してください',
            $errors->first('password')
        );
    }

    public function test_不正なメール形式()
    {
        $data = [
            'email' => 'wrongemail',
            'password' => 'Pass-1234'
        ];
        $response = $this->post(route('register', $data));
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスは「ユーザー名@ドメイン」形式で入力してください'
        ]);
    }

    public function test_パスワードの文字数不足()
    {
        $data = [
            'email' => 'testuser@testuser.com',
            'password' => 'Pass-1'
        ];
        $response = $this->post(route('register', $data));
        $response->assertSessionHasErrors([
            'password' => 'パスワードは、8文字以上で指定してください。'
        ]);
    }

    public function test_パスワードに大文字が含まれない()
    {
        $data = [
            'email' => 'testuser@testuser.com',
            'password' => 'pass-1234'
        ];
        $response = $this->post(route('register', $data));
        $response->assertSessionHasErrors([
            'password' => 'パスワードは、少なくとも大文字と小文字を1つずつ含める必要があります。'
        ]);
    }

    public function test_パスワードに小文字が含まれない()
    {
        $data = [
            'email' => 'testuser@testuser.com',
            'password' => 'PASS-1234'
        ];
        $response = $this->post(route('register', $data));
        $response->assertSessionHasErrors([
            'password' => 'パスワードは、少なくとも大文字と小文字を1つずつ含める必要があります。'
        ]);
    }

    public function test_パスワードに記号が含まれない()
    {
        $data = [
            'email' => 'testuser@testuser.com',
            'password' => 'Pass1234'
        ];
        $response = $this->post(route('register', $data));
        $response->assertSessionHasErrors([
            'password' => 'パスワードは、少なくとも1つの記号が含まれていなければなりません。'
        ]);
    }

    public function test_パスワードに数字が含まれない()
    {
        $data = [
            'email' => 'testuser@',
            'password' => 'Pass-word'
        ];
        $response = $this->post(route('register', $data));
        $response->assertSessionHasErrors([
            'password' => 'パスワードは、少なくとも1つの数字が含まれていなければなりません。'
        ]);
    }

    public function test_削除済みユーザのメールアドレス再登録不可()
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

        $response = $this->post(route('register', $data));

        $response->assertSessionHasErrors([
            'email' => 'このメールアドレスは登録できません。',
        ]);
    }

    public function test_入力が適切()
    {
        $data = [
            'email' => 'test@testuser.com',
            'password' => 'Pass-1234'
        ];
        $response = $this->post(route('register', $data));
        $response->assertRedirect(route('profile.show'));
    }
}
