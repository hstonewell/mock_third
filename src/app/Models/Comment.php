<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    //コメント表示のフィルター
    public function scopeForComment($query, $itemId)
    {
        return $query->where('item_id', $itemId)->with('user.userProfile')->orderBy('created_at', 'asc');
    }
}
