<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Item;
use App\Models\PurchasedItem;

class MyPageController extends Controller
{
    public function show()
    {
        $userId = Auth::id();
        $user = Auth::user();

        $userProfile = $user->userProfile;

        $sellingItems = Item::sellingItems($userId)->get();
        $purchasedItems = PurchasedItem::purchasedItems($userId)->with('item')->get();

        return view('mypage', compact('userProfile', 'sellingItems', 'purchasedItems'));
    }
}
