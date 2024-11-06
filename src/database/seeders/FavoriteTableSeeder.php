<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Favorite;
use App\Models\Item;

class FavoriteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $items = Item::all();
        foreach ($users as $user) {
            $otherItems = $items->where('user_id', '!=', $user->id);

            $otherItems->random(10)->each(function ($item) use ($user) {
                Favorite::create([
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                ]);
            });
        }
    }
}
