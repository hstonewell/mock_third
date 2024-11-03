<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class PurchasedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'item_id',
    ];

    //リレーション
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id', 'user_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public static function purchase($item_id)
    {
        $param = [
            'buyer_id' => Auth::id(),
            'item_id' => $item_id,
        ];

        $purchase = PurchasedItem::create($param);

        $item = Item::findOrFail($item_id);
        $item->sold_out = true;
        $item->save();

        return $purchase;
    }

    //マイページの「購入した商品」
    public function scopePurchasedItems($query, $userId = null)
    {
        $userId = $userId ?? Auth::id();

        return $query->where('buyer_id', $userId)->orderBy('created_at', 'desc');
    }
}
