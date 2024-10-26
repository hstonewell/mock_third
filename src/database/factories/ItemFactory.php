<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Condition;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'brand_id' => Brand::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'condition_id' => Condition::inRandomOrder()->first()->id,
            'item_name' => 'サンプル商品名',
            'price' => fake()->numberBetween(1000, 100000),
            'description' => fake()->sentence(),
            'image' => 'img/sample-image.png'
        ];
    }
}
