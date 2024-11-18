<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ladiesFashion = Category::create(['category_name' => 'レディースファッション']);
        $mensFashion = Category::create(['category_name' => 'メンズファッション']);
        $beauty = Category::create(['category_name' => '美容・健康']);


        $param = [
            'category_name' => 'トップス',
            'parent_id' => $ladiesFashion->id,
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'ボトムス',
            'parent_id' => $ladiesFashion->id,
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'シューズ',
            'parent_id' => $ladiesFashion->id,
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'アクセサリー',
            'parent_id' => $ladiesFashion->id,
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'トップス',
            'parent_id' => $mensFashion->id,
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'ボトムス',
            'parent_id' => $mensFashion->id,
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'シューズ',
            'parent_id' => $mensFashion->id,
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'アクセサリー',
            'parent_id' => $mensFashion->id,
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'レディース化粧品',
            'parent_id' => $beauty->id,
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'メンズ化粧品',
            'parent_id' => $beauty->id,
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => '健康用品',
            'parent_id' => $beauty->id,
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'エンタメ・ホビー',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => '家具・インテリア',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => '家電・ガジェット',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'キッズ・ベビー用品',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'その他',
        ];
        DB::table('categories')->insert($param);
    }
}
