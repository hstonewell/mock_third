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
        $item = Item::with('user', 'brand', 'category', 'condition')->find($id);
        $userProfile = Auth::user()->userProfile;

        return view ('purchase', compact('item', 'userProfile'));
    }

    public function create($item_id)
    {
        PurchasedItem::purchase($item_id);

        return redirect()->route('index');
    }
}
