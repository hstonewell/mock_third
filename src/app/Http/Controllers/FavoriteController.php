<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function create($item_id)
    {
        Favorite::favorite(Auth::id(), $item_id);

        return redirect()->route('detail.item', ['item_id' => $item_id]);
    }

    public function delete($item_id) {
        Favorite::where('user_id', Auth::id())->where('item_id', $item_id)->delete();

        return redirect()->route('detail.item', ['item_id' => $item_id]);
    }
}
