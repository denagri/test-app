<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
        ['kind' => 'ファッション'],
        ['kind' => '家電'],
        ['kind' => 'インテリア'],
        ['kind' => 'レディース'],
        ['kind' => 'メンズ'],
        ['kind' => 'コスメ'],
        ['kind' => '本'],
        ['kind' => 'ゲーム'],
        ['kind' => 'スポーツ'],
        ['kind' => 'キッチン'],
        ['kind' => 'ハンドメイド'],
        ['kind' => 'アクセサリー'],
        ['kind' => 'おもちゃ'],
        ['kind' => 'ベビー・キッズ'],
    ];

    foreach ($categories as $category) {
        \App\Models\Category::create($category);
    }
    }
}
