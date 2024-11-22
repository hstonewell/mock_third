<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
    public $categoryId;
    public $brandName;
    public $conditionId;


    public function mount()
    {
        $this->categoryId = null; // 初期化
        $this->conditionId = null; // 初期化
        $this->brandName = '';
    }

    //画面表示
    public function render()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $conditions = Condition::all();

        return view('livewire.item-selling-form', compact('categories', 'conditions'));
    }

    //バリデーション
    public function rules()
    {
        return [
            'item_name' => 'required|string|max:191',
            'price' => 'required|integer|min:300|max:9999999',
            'description' => 'nullable|string|max:1000',
            'itemImage' => 'required|image|max:5020',
            'brandName' => 'nullable|string|max:191',
            'categoryId' => 'nullable|exists:categories,id',
            'conditionId' => 'nullable|exists:conditions,id',
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

    //保存処理
    public function save()
    {
        $validated = $this->validate();

        $itemImage = $this->itemImage->store('item-images', 'public');
        $itemImageUrl = Storage::url($itemImage);

        $data = array_merge($validated, [
            'user_id' => Auth::id(),
            'price' => $this->price,
            'image' => $itemImageUrl,
            'brand_name' => $this->brandName ?? null,
            'category_id' => $this->categoryId ?: null,
            'condition_id' => $this->conditionId ?: null,
        ]);

        Item::create($data);

        return redirect()->route('index');
    }
}
