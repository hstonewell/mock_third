<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Item;
use App\Models\UserProfile;

use App\Http\Requests\UserProfileRequest;

class UserProfileController extends Controller
{
    public function showAddress($item_id)
    {
        $item = Item::find($item_id);
        $loginUserProfile = Auth::user()->userProfile;

        return view('address', compact('item', 'loginUserProfile'));
    }

    public function createAddress(UserProfileRequest $request, $item_id)
    {
        $user = Auth::user();

        UserProfile::updateOrCreateAddress($user->id, $request->validated());

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }

    public function showProfile()
    {
        $userProfile = Auth::user()->userProfile;

        $imageUrl = $userProfile ? $userProfile->getProfileImageUrl() : asset('img/default-user-icon.svg');

        return view('profile', compact('imageUrl', 'userProfile'));
    }

}
