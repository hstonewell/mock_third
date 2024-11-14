<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;

class ItemSellingForm extends Component
{
    use WithFileUploads;

    public $item_name;
    public $price;
    public $description;
    public $itemImage;
    public $category_id;
    public $brand_id;
    public $condition_id;

    //画面表示
    public function render()
    {
        $brands = Brand::all();
        $categories = Category::all();
        $conditions = Condition::all();

        return view('livewire.item-selling-form', compact('brands', 'categories', 'conditions'));
    }

    //バリデーション
    public function rules()
    {
        return [
            'item_name' => 'required|string|max:191',
            'price' => 'required|string',
            'description' => 'nullable|string|max:1000',
            'itemImage' => 'required|image|max:5020',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'condition_id' => 'nullable|exists:conditions,id',
        ];
    }

    //商品画像のプレビュー表示
    public function updatedItemImage()
    {
        $this->validateOnly('itemImage');
    }

    public function closePreviewImage()
    {
        $this->itemImage = null;
    }

    //販売価格のカンマ表示
    public function updatedPrice($value)
    {
        $this->price = number_format(str_replace(',', '', $value));
    }

    //保存処理
    public function save()
    {
        $validated = $this->validate();

        $itemImage = $this->itemImage->store('item-images', 'public');
        $itemImageUrl = Storage::url($itemImage);
        $formattedPrice = $this->price = str_replace(',', '', $this->price);

        $data = (array_merge($validated, [
            'user_id' => Auth::id(),
            'price' => $formattedPrice,
            'image' => $itemImageUrl,
            'brand_id' => $this->brand_id ?? null,
            'category_id' => $this->category_id ?? null,
            'condition_id' => $this->condition_id ?? null,
        ]));

        Item::create($data);

        return redirect()->route('index');
    }
}
