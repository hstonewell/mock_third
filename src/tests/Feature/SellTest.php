<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Category;
use App\Models\Condition;

use Livewire\Livewire;
use Livewire\WithFileUploads;
use App\Livewire\ItemSellingForm;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class SellTest extends TestCase
{
    use RefreshDatabase;
    use WithFileUploads;

    public function setup(): void
    {
        parent::setup();

        User::factory(5)->create();
        $user = User::first();
        $this->actingAs($user);

        Storage::fake('public');
    }

    public function test_商品名が空()
    {
        $file = UploadedFile::fake()->create('sample-image.jpg', 100);

        Livewire::test(ItemSellingForm::class)
            ->set('item_name', '')
            ->set('price', '1000')
            ->set('description', '商品説明')
            ->set('itemImage', $file)
            ->set('brandName', 'テストのブランド名')
            ->set('categoryId', Category::first())
            ->set('conditionId', Condition::first())
            ->call('save')
            ->assertHasErrors(['item_name' => 'required']);
    }
    public function test_商品名が長すぎる()
    {
        $file = UploadedFile::fake()->create('sample-image.jpg', 100);

        Livewire::test(ItemSellingForm::class)
            ->set('item_name', str_repeat('a', 192))
            ->set('price', '1000')
            ->set('description', '商品説明')
            ->set('itemImage', $file)
            ->set('brandName', 'テストのブランド名')
            ->set('categoryId', Category::first())
            ->set('conditionId', Condition::first())
            ->call('save')
            ->assertHasErrors(['item_name' => 'max']);
    }

    public function test_値段が空欄()
    {
        $file = UploadedFile::fake()->create('sample-image.jpg', 100);

        Livewire::test(ItemSellingForm::class)
            ->set('item_name', 'テスト商品名')
            ->set('price', '')
            ->set('description', '商品説明')
            ->set('itemImage', $file)
            ->set('brandName', 'テストのブランド名')
            ->set('categoryId', Category::first())
            ->set('conditionId', Condition::first())
            ->call('save')
            ->assertHasErrors(['price' => 'required']);
    }

    public function test_値段が安すぎる()
    {
        $file = UploadedFile::fake()->create('sample-image.jpg', 100);

        Livewire::test(ItemSellingForm::class)
            ->set('item_name', 'テスト商品名')
            ->set('price', '1')
            ->set('description', '商品説明')
            ->set('itemImage', $file)
            ->set('brandName', 'テストのブランド名')
            ->set('categoryId', Category::first())
            ->set('conditionId', Condition::first())
            ->call('save')
            ->assertHasErrors(['price' => 'min']);
    }

    public function test_値段が高すぎる()
    {
        $file = UploadedFile::fake()->create('sample-image.jpg', 100);

        Livewire::test(ItemSellingForm::class)
            ->set('item_name', 'テスト商品名')
            ->set('price', '10000000')
            ->set('description', '商品説明')
            ->set('itemImage', $file)
            ->set('brandName', 'テストのブランド名')
            ->set('categoryId', Category::first())
            ->set('conditionId', Condition::first())
            ->call('save')
            ->assertHasErrors(['price' => 'max']);
    }

    public function test_値段が数字ではない()
    {
        $file = UploadedFile::fake()->create('sample-image.jpg', 100);

        Livewire::test(ItemSellingForm::class)
            ->set('item_name', 'テスト商品名')
            ->set('price', 'abc')
            ->set('description', '商品説明')
            ->set('itemImage', $file)
            ->set('brandName', 'テストのブランド名')
            ->set('categoryId', Category::first())
            ->set('conditionId', Condition::first())
            ->call('save')
            ->assertHasErrors(['price' => 'integer']);
    }

    public function test_商品説明が空()
    {
        $file = UploadedFile::fake()->create('sample-image.jpg', 100);

        Livewire::test(ItemSellingForm::class)
            ->set('item_name', 'テスト商品名')
            ->set('price', '1000')
            ->set('description', '')
            ->set('itemImage', $file)
            ->set('brandName', 'テストのブランド名')
            ->set('categoryId', Category::first())
            ->set('conditionId', Condition::first())
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('index'));
    }

    public function test_商品説明が長すぎる()
    {
        $file = UploadedFile::fake()->create('sample-image.jpg', 100);

        Livewire::test(ItemSellingForm::class)
            ->set('item_name', 'テスト商品名')
            ->set('price', '1000')
            ->set('description', str_repeat('a', 1001))
            ->set('itemImage', $file)
            ->set('brandName', 'テストのブランド名')
            ->set('categoryId', Category::first())
            ->set('conditionId', Condition::first())
            ->call('save')
            ->assertHasErrors(['description' => 'max']);
    }

    public function test_画像が空()
    {
        Livewire::test(ItemSellingForm::class)
            ->set('item_name', 'テスト商品名')
            ->set('price', '1000')
            ->set('description', '商品説明')
            ->set('itemImage', '')
            ->set('brandName', 'テストのブランド名')
            ->set('categoryId', Category::first())
            ->set('conditionId', Condition::first())
            ->call('save')
            ->assertHasErrors(['itemImage' => 'required']);
    }

    public function test_画像サイズが大きすぎる()
    {
        $file = UploadedFile::fake()->create('sample-image.jpg', 10000);

        Livewire::test(ItemSellingForm::class)
            ->set('item_name', 'テスト商品名')
            ->set('price', '1000')
            ->set('description', '商品説明')
            ->set('itemImage', $file)
            ->set('brandName', 'テストのブランド名')
            ->set('categoryId', Category::first())
            ->set('conditionId', Condition::first())
            ->call('save')
            ->assertHasErrors(['itemImage' => 'max']);
    }

    public function test_ブランド名が空()
    {
        $file = UploadedFile::fake()->create('sample-image.jpg', 100);

        Livewire::test(ItemSellingForm::class)
            ->set('item_name', 'テスト商品名')
            ->set('price', '1000')
            ->set('description', '商品説明')
            ->set('itemImage', $file)
            ->set('brandName', '')
            ->set('categoryId', Category::first())
            ->set('conditionId', Condition::first())
            ->call('save')
            ->assertRedirect(route('index'));
    }
    public function test_ブランド名が長すぎる()
    {
        $file = UploadedFile::fake()->create('sample-image.jpg', 100);

        Livewire::test(ItemSellingForm::class)
            ->set('item_name', 'テスト商品名')
            ->set('price', '1000')
            ->set('description', '商品説明')
            ->set('itemImage', $file)
            ->set('brandName', str_repeat('a', 192))
            ->set('categoryId', Category::first())
            ->set('conditionId', Condition::first())
            ->call('save')
            ->assertHasErrors(['brandName' => 'max']);
    }

    public function test_カテゴリが空()
    {
        $file = UploadedFile::fake()->create('sample-image.jpg', 100);

        Livewire::test(ItemSellingForm::class)
            ->set('item_name', 'テスト商品名')
            ->set('price', '1000')
            ->set('description', '商品説明')
            ->set('itemImage', $file)
            ->set('brandName', 'テストのブランド名')
            ->set('categoryId', '')
            ->set('conditionId', Condition::first())
            ->call('save')
            ->assertRedirect(route('index'));
    }

    public function test_商品状態が空()
    {
        $file = UploadedFile::fake()->create('sample-image.jpg', 100);

        Livewire::test(ItemSellingForm::class)
            ->set('item_name', 'テスト商品名')
            ->set('price', '1000')
            ->set('description', '商品説明')
            ->set('itemImage', $file)
            ->set('brandName', 'テストのブランド名')
            ->set('categoryId', Category::first())
            ->set('conditionId', '')
            ->call('save')
            ->assertRedirect(route('index'));
    }

    public function test_入力が適切()
    {
        $file = UploadedFile::fake()->create('sample-image.jpg', 100);

        Livewire::test(ItemSellingForm::class)
            ->set('item_name', 'テスト商品名')
            ->set('price', '1000')
            ->set('description', '商品説明')
            ->set('itemImage', $file)
            ->set('brandName', 'テストのブランド名')
            ->set('categoryId', Category::first())
            ->set('conditionId', Condition::first())
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('index'));
    }
}
