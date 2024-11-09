<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'postcode',
        'address',
        'building',
        'image',
    ];

    //リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //住所登録
    public static function updateOrCreateAddress($userId, $data)
    {
        return self::updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }

    //アイコン
    public function getProfileImageUrl()
    {
        return $this->image ? Storage::url($this->image) : asset('img/default-user-icon.svg');
    }
}
