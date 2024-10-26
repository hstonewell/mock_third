<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            'category_name' => 'レディースファッション',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'メンズファッション',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'コスメ/美容',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'キッズ/ベビー',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'エンタメ/ホビー',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'インテリア/日用品',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => '家電',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => '食品/飲料',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => '自動車/バイク',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'その他',
        ];
        DB::table('categories')->insert($param);
    }
}
