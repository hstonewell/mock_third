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
        $item = Item::with('user', 'category', 'condition')->withCount('favorites')->findOrFail($id);

        $favorites = Auth::check() && $item->favorites->contains('user_id', Auth::id());

        $comments = Comment::forComment($id)->get();

        $category = $item->category;
        $parentCategory = $category && $category->parent ? $category->parent->category_name : null;
        $childCategory = $category ? $category->category_name : null;

        $condition = $item->condition ? $item->condition->condition : null;

        return view('detail', compact('item', 'favorites', 'comments', 'category', 'parentCategory', 'childCategory', 'condition'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $searchResults = Item::KeywordSearch($keyword)
        ->where('sold_out', false)
        ->get();
        $text = $request->keyword . "の検索結果";

        session()->flash('fs_msg', $text);

        $recommendItems = Item::recommendItems()->get();

        if (Auth::check()) {
            $userId = $request->user()->id;
            $favoriteItems = Favorite::favoriteItems($userId)->with('item')->get();

            return view('index', compact('searchResults', 'recommendItems', 'favoriteItems'));
        }

        return view('index', compact('searchResults', 'recommendItems', 'text'));
    }

}
