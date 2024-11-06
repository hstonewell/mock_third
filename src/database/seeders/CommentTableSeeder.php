<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Comment;
use App\Models\Item;

class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //自分の出品した商品へのコメント
        $users = User::with('items')->get();

        foreach ($users as $user) {
            foreach ($user->items as $item) {
                Comment::create([
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                    'comment' => '出品者からのコメント',
                ]);
            }
        }

        //他人の出品した商品へのコメント
        $items = Item::all();
        foreach ($users as $user) {
            $otherItems = $items->where('user_id', '!=', $user->id);

            $otherItems->random(5)->each(function ($item) use ($user) {
                Comment::create([
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                    'comment' => '購入検討者からのコメント',
                ]);
            });
        }
    }
}
