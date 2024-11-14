<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\UserProfile;

class ProfileForm extends Component
{
    use WithFileUploads;

    public $name;
    public $postcode;
    public $address;
    public $building;
    public $profileImage;
    public $profileImageUrl;

    //画面表示
    public function mount()
    {
        $userProfile = Auth::user()->userProfile;

        if ($userProfile)
        {
            $this->name = $userProfile->name;
            $this->postcode = $userProfile->postcode;
            $this->address = $userProfile->address;
            $this->building = $userProfile->building;
            $this->profileImageUrl = $userProfile->getProfileImageUrl();
        } else {
            $this->profileImageUrl = asset('img/default-user-icon.svg');
        }
    }

    public function render()
    {
        return view('livewire.profile-form');
    }

    //バリデーション
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:191',
            'postcode' => 'required|digits:7|regex:/^[0-9]+$/',
            'address' => 'required|string|max:191',
            'building' => 'nullable|string|max:191',
            'profileImage' => 'nullable|image|max:5020',
        ];
    }

    //プロフィール画像のプレビュー表示
    public function updatedProfileImage()
    {
        $this->validateOnly('profileImage');
    }

    //保存処理
    public function save()
    {
        $validated = $this->validate();

        if ($this->profileImage) {
            $profileImagePath = $this->profileImage->store('profile-images', 'public');
            $profileImageUrl = Storage::url($profileImagePath);
        } else {
            $profileImageUrl = $this->profileImageUrl;
        }

        $data = (array_merge($validated, [
            'user_id' => Auth::id(),
            'image' => $profileImagePath ?? Auth::user()->userProfile->image ?? null,
        ]));

        UserProfile::updateOrCreate(['user_id' => Auth::id()], $data);

        return redirect()->route('mypage.show');
    }
}

