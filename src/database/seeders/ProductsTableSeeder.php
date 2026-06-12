<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product1=Product::create([
            'sell_user_id' =>1,
            'name' =>'腕時計',
            'price' =>'15000',
            'brand' =>'Rolax',
            'image_path' =>'clock.jpg',
            'explanation' =>'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' =>1,
        ]);
        $product1->categories()->attach([1,5,12]);

        $product2 = Product::create([
            'sell_user_id' =>1,
            'name' =>'HDD',
            'price' =>'5000',
            'brand' =>'西芝',
            'image_path' =>'HDD.jpg',
            'explanation' =>'高速で信頼性の高いハードディスク',
            'condition_id' =>2,
        ]);
        $product2->categories()->attach([2]);

        $product3 = Product::create([
            'sell_user_id' =>1,
            'name' =>'玉ねぎ3束',
            'price' =>'300',
            'brand' =>'なし',
            'image_path' =>'onion.jpg',
            'explanation' =>'新鮮な玉ねぎ3束のセット',
            'condition_id' =>3,
        ]);
        $product3->categories()->attach([10]);

        $product4 = Product::create([
            'sell_user_id' =>1,
            'name' =>'革靴',
            'price' =>'4000',
            'brand' =>null,
            'image_path' =>'shoes.jpg',
            'explanation' =>'クラシックなデザインの革靴',
            'condition_id' =>4,
        ]);
        $product4->categories()->attach([1,5]);

        $product5 = Product::create([
            'sell_user_id' =>1,
            'name' =>'ノートPC',
            'price' =>'45000',
            'brand' =>null,
            'image_path' =>'laptop.jpg',
            'explanation' =>'高性能なノートパソコン',
            'condition_id' =>1,
        ]);
        $product5->categories()->attach([2]);

        $product6 = Product::create([
            'sell_user_id' =>1,
            'name' =>'マイク',
            'price' =>'8000',
            'brand' =>'なし',
            'image_path' =>'mic.jpg',
            'explanation' =>'高音質のレコーディング用マイク',
            'condition_id' =>2,
        ]);
        $product6->categories()->attach([2]);

        $product7 = Product::create([
            'sell_user_id' =>1,
            'name' =>'ショルダーバッグ',
            'price' =>'3500',
            'brand' =>null,
            'image_path' =>'bag.jpg',
            'explanation' =>'おしゃれなショルダーバッグ',
            'condition_id' =>3,
        ]);
        $product7->categories()->attach([1,4]);

        $product8 = Product::create([
            'sell_user_id' =>1,
            'name' =>'タンブラー',
            'price' =>'500',
            'brand' =>'なし',
            'image_path' =>'Tumbler.jpg',
            'explanation' =>'使いやすいタンブラー',
            'condition_id' =>4,
        ]);
        $product8->categories()->attach([10]);

        $product9 = Product::create([
            'sell_user_id' =>1,
            'name' =>'コーヒーミル',
            'price' =>'4000',
            'brand' =>'Starbacks',
            'image_path' =>'Coffee.jpg',
            'explanation' =>'手動のコーヒーミル',
            'condition_id' =>1,
        ]);
        $product9->categories()->attach([3,10]);

        $product10 = Product::create([
            'sell_user_id' =>1,
            'name' =>'メイクセット',
            'price' =>'2500',
            'brand' =>null,
            'image_path' =>'makeup.jpg',
            'explanation' =>'便利なメイクアップセット',
            'condition_id' =>2,
        ]);
        $product10->categories()->attach([1,6]);
    }
}
