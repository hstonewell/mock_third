<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand_id',
        'category_id',
        'price',
        'description',
        'item_image_id',
        'condition'
    ];

    //各モデルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function brands()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'item_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'item_id');
    }

    public function item_images()
    {
        return $this->hasMany(ItemImage::class, 'item_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'item_id');
    }

    //検索
    public function scopeCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }
    public function scopeBrand($query, $condition)
    {
        return $query->where('brand', $condition);
    }
    public function scopeCategory($query, $condition)
    {
        return $query->where('condition', $condition);
    }
}
