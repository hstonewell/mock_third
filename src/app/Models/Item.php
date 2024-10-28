<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'brand_id',
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

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
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
    public function scopeRecommendItems ($query)
    {
        return $query->where('sold_out', false)->orderBy('created_at', 'desc');
    }

    //検索
    public function scopeKeywordSearch($query, $keyword)
    {
        return $query->where('item_name', 'like', '%' . $keyword . '%');
    }
    public function scopeConditionSearch($query, $condition)
    {
        return $query->where('condition', $condition);
    }
    public function scopeBrandSearch($query, $brand)
    {
        return $query->where('brand', $brand);
    }
    public function scopeCategorySearch($query, $category)
    {
        return $query->where('Category', $category);
    }
}
