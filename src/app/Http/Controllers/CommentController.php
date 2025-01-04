<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

use Spatie\Permission\Traits\HasRoles;

class CommentController extends Controller
{
    use HasRoles;

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

        if (Auth::user()->hasRole('admin') || $comment->user_id === Auth::id()) {
            $comment->delete();
            return redirect()->route('item.detail', ['item_id' => $comment->item_id])
                ->with('success', 'コメントを削除しました。');
        }

        return redirect()->route('item.detail', ['item_id' => $comment->item_id])
            ->with('error', 'このコメントを削除する権限がありません。');
    }
}
