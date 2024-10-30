<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class CommentController extends Controller
{
    public function create(Request $request) {

        $comment = $request->only('item_id', 'comment');
        $comment['user_id'] = Auth::id();

        Comment::create($comment);

        return redirect()->route('item.detail', ['item_id' => $request->input('item_id')]);
    }
}