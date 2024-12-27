<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class CommentController extends Controller
{
    public function create(CommentRequest $request)
    {
        $comment = $request->only('item_id', 'comment');
        $comment['user_id'] = Auth::id();

        Comment::create($comment);

        return redirect()->route('item.detail', ['item_id' => $request->input('item_id')]);
    }

    public function destroy($comment_id)
    {
        $comment = Comment::where('id', $comment_id)
            ->first();

        $itemId = $comment->item_id;

        $comment->delete();

        return redirect()->route('item.detail', ['item_id' => $itemId]);
    }
}
