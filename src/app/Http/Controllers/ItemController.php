<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $recommendItems = Item::recommendItems()->get();

        if (Auth::check()) {
            $userId = $request->user()->id;
            $favoriteItems = Favorite::favoriteItems($userId)->with('item')->get();

            return view('index', compact('recommendItems', 'favoriteItems'));
        }

        return view('index', compact('recommendItems'));
    }

    public function detail($id)
    {
        $item = Item::with('user', 'brand', 'category', 'condition')->withCount('favorites')->findOrFail($id);

        $favorites = Auth::check() && $item->favorites->contains('user_id', Auth::id());

        $comments = Comment::forComment($id)->get();

        return view('detail', compact('item', 'favorites', 'comments'));
    }

}
