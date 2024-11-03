<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
    ];

    public static function favorite($user_id, $item_id)
    {
        $param = [
            'user_id' => $user_id,
            'item_id' => $item_id,
        ];

        $favorite = Favorite::create($param);

        return $favorite;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    //トップページの「おすすめ」
    public function scopeFavoriteItems($query, $userId = null)
    {
        $userId = $userId ?? Auth::id();

        return $query->where('user_id', $userId)->orderBy('created_at', 'desc');
    }
}
