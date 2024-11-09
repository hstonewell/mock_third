<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileImageUpload extends Component
{
    use WithFileUploads;

    public $image;
    public $imageUrl;

    public function mount()
    {
        $this->imageUrl = Auth::user()->userProfile->getProfileImageUrl();
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
        return view('livewire.profile-image-upload');
    }
}
