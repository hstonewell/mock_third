<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'brand_name',
        'category_id',
        'condition_id',
        'item_name',
        'price',
        'description',
        'image',
        'sold_out',
    ];

    //売り切れかどうかの判定
    public function isSoldOut()
    {
        return $this->sold_out;
    }

    //各モデルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class, 'condition_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'item_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'item_id');
    }

    public function purchasedItems()
    {
        return $this->hasMany(PurchasedItem::class, 'item_id');
    }

    //トップページの「おすすめ」
    public function scopeRecommendItems($query, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        return $query->where('sold_out', false)->where('user_id', '!=', $userId)->orderBy('created_at', 'desc');
    }

    //マイページの「出品した商品」
    public function scopeSellingItems($query, $userId = null)
    {
        $userId = $userId ?? Auth::id();

        return $query->where('user_id', $userId)->orderBy('created_at', 'desc');
    }

    //検索
    public function scopeKeywordSearch($query, $keyword)
    {
        $userId = $userId ?? Auth::id();
        return $query->where('item_name', 'like', '%' . $keyword . '%')->where('sold_out', false)->orderBy('created_at', 'desc');
    }
}
