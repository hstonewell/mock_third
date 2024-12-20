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
        return $this->belongsTo(User::class, 'buyer_id', 'user_id')->withTrashed();
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public static function purchase($itemId)
    {
        $param = [
            'buyer_id' => Auth::id(),
            'item_id' => $itemId,
        ];

        $purchase = PurchasedItem::create($param);

        $item = Item::findOrFail($itemId);
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
