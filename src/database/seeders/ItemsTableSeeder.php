<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'user_id' => 1,
                'condition_id' => 1,
                'item_name' => '腕時計',
                'brand_name' => 'Rolax',
                'price' => 15000,
                'item_description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image' => 'storage/app/public/items/watch.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'condition_id' => 2,
                'item_name' => 'HDD',
                'brand_name' => '西芝',
                'price' => 5000,
                'item_description' => '高速で信頼性の高いハードディスク',
                'image' => 'storage/app/public/items/HDD.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'condition_id' => 3,
                'item_name' => '玉ねぎ3束',
                'brand_name' => 'なし',
                'price' => 300,
                'item_description' => '新鮮な玉ねぎ3束のセット',
                'image' => 'storage/app/public/items/onion.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'condition_id' => 4,
                'item_name' => '革靴',
                'brand_name' => 'null',
                'price' => 4000,
                'item_description' => 'クラシックなデザインの革靴',
                'image' => 'storage/app/public/items/leather-shoes.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'condition_id' => 1,
                'item_name' => 'ノートPC',
                'brand_name' => 'null',
                'price' => 45000,
                'item_description' => '高性能なノートパソコン',
                'image' => 'storage/app/public/items/note-pc.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'condition_id' => 2,
                'item_name' => 'マイク',
                'brand_name' => 'なし',
                'price' => 8000,
                'item_description' => '高音質のレコーディング用マイク',
                'image' => 'storage/app/public/items/microphone.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'condition_id' => 3,
                'item_name' => 'ショルダーバッグ',
                'brand_name' => 'null',
                'price' => 3500,
                'item_description' => 'おしゃれなショルダーバッグ',
                'image' => 'storage/app/public/items/shoulder-bag.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'condition_id' => 4,
                'item_name' => 'タンブラー',
                'brand_name' => 'なし',
                'price' => 500,
                'item_description' => '使いやすいタンブラー',
                'image' => 'storage/app/public/items/tumbler.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'condition_id' => 1,
                'item_name' => 'コーヒーミル',
                'brand_name' => 'Starbacks',
                'price' => 4000,
                'item_description' => '手動のコーヒーミル',
                'image' => 'storage/app/public/items/coffee-mill.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'condition_id' => 2,
                'item_name' => 'メイクセット',
                'brand_name' => 'null',
                'price' => 2500,
                'item_description' => '便利なメイクアップセット',
                'image' => 'storage/app/public/items/makeup-set.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('items')->insert($items);
    }
}
