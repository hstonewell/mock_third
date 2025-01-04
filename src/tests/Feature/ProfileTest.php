<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Livewire\Livewire;
use App\Livewire\ProfileForm;

use App\Models\User;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();

        User::factory()->create();

        $user = User::first();
        $this->actingAs($user);
    }

    public function test_名前が空欄()
    {
        Livewire::test(ProfileForm::class)
            ->set('name', '')
            ->set('postcode', '1234567')
            ->set('address', '東京都◯◯区××12-3')
            ->set('building', 'テストマンション101')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_名前が長すぎる()
    {
        Livewire::test(ProfileForm::class)
            ->set('name', str_repeat('a', 192))
            ->set('postcode', '1234567')
            ->set('address', '東京都◯◯区××12-3')
            ->set('building', 'テストマンション101')
            ->call('save')
            ->assertHasErrors(['name' => 'max']);
    }

    public function test_郵便番号が空()
    {
        Livewire::test(ProfileForm::class)
            ->set('name', 'テストたろう')
            ->set('postcode', '')
            ->set('address', '東京都◯◯区××12-3')
            ->set('building', 'テストマンション101')
            ->call('save')
            ->assertHasErrors(['postcode' => 'required']);
    }

    public function test_郵便番号の桁数が不正()
    {
        Livewire::test(ProfileForm::class)
            ->set('name', 'テストたろう')
            ->set('postcode', '12345678')
            ->set('address', '東京都◯◯区××12-3')
            ->set('building', 'テストマンション101')
            ->call('save')
            ->assertHasErrors(['postcode' => 'digits']);
    }

    public function test_郵便番号に数字以外が含まれる()
    {
        Livewire::test(ProfileForm::class)
            ->set('name', 'テストたろう')
            ->set('postcode', '1234abc')
            ->set('address', '東京都◯◯区××12-3')
            ->set('building', 'テストマンション101')
            ->call('save')
            ->assertHasErrors(['postcode' => 'regex']);
    }

    public function test_住所が空()
    {
        Livewire::test(ProfileForm::class)
            ->set('name', 'テストたろう')
            ->set('postcode', '1234567')
            ->set('address', '')
            ->set('building', 'テストマンション101')
            ->call('save')
            ->assertHasErrors(['address' => 'required']);
    }

    public function test_住所が長すぎる()
    {
        Livewire::test(ProfileForm::class)
            ->set('name', 'テストたろう')
            ->set('postcode', '1234567')
            ->set('address', str_repeat('a', 192))
            ->set('building', 'テストマンション101')
            ->call('save')
            ->assertHasErrors(['address' => 'max']);
    }

    public function test_建物名が空()
    {
        Livewire::test(ProfileForm::class)
            ->set('name', 'テストたろう')
            ->set('postcode', '1234567')
            ->set('address', '東京都◯◯区××12-3')
            ->set('building', '')
            ->call('save')
            ->assertRedirect(route('mypage.show'));
    }

    public function test_建物名が長すぎる()
    {
        Livewire::test(ProfileForm::class)
            ->set('name', 'テストたろう')
            ->set('postcode', '1234567')
            ->set('address', '東京都◯◯区××12-3')
            ->set('building', str_repeat('a', 192))
            ->call('save')
            ->assertHasErrors(['building' => 'max']);
    }

    public function test_入力が適切()
    {
        Livewire::test(ProfileForm::class)
            ->set('name', 'テストたろう')
            ->set('postcode', '1234567')
            ->set('address', '東京都◯◯区××12-3')
            ->set('building', 'テストマンション101')
            ->call('save')
            ->assertRedirect(route('mypage.show'));
    }
}
