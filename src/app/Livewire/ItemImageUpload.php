<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ItemImageUpload extends Component
{
    use WithFileUploads;

    public $itemImage;

    public function updatedItemImage()
    {
        $this->validate([
            'itemImage' => 'image|max:1024', // 1MB Max
        ]);
    }

    public function closePreviewImage() {
        $this->itemImage = null;
    }

    public function save()
    {
        $this->itemImage->store('item-images', 'public');
    }

    public function render()
    {
        return view('livewire.item-image-upload');
    }
}
