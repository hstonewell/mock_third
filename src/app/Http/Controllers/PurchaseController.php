<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Item;
use App\Models\PurchasedItem;

class PurchaseController extends Controller
{
    public function show($id)
    {
        $item = Item::with('user', 'category', 'condition')->find($id);
        $userProfile = Auth::user()->userProfile;

        $hasUserAddress = $userProfile && $userProfile->address && $userProfile->postcode;

        return view ('purchase', compact('item', 'userProfile', 'hasUserAddress'));
    }

    public function create($item_id)
    {
        PurchasedItem::purchase($item_id);

        return redirect()->route('index');
    }
}
