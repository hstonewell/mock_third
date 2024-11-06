<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ImageUpload extends Component
{
    use WithFileUploads;

    public $image;
    public $imageUrl;

    public function mount()
    {
        $this->imageUrl = Auth::user()->userProfile->getImageUrl();
    }

    public function updatedImage()
    {
        $this->save();
    }

    public function save()
    {
        $this->validate([
            'image' => 'image|max:1024',
        ]);

        $path = $this->image->store('images', 'public');

        // データベースのUserProfileにパスを保存
        Auth::user()->userProfile->update(['image' => $path]);

        // プレビュー用のパスを更新
        $this->imageUrl = Storage::url($path);
    }

    public function render()
    {
        $user = Auth::user();

        return view('livewire.image-upload');
    }
}
