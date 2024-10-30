<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comment;

class ItemController extends Controller
{
    public function index()
    {
        $recommendItems = Item::recommendItems()->get();

        return view('index', compact('recommendItems'));
    }

    public function detail($id)
    {
        $item = Item::with('user', 'brand', 'category', 'condition')->find($id);

        $comments = Comment::forComment($id)->get();

        return view('detail', compact('item', 'comments'));
    }

}
