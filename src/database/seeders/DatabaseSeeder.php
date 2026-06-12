<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'name' => 'テストユーザー（確認用）',
            'email' => 'test@example.com',
        ]);

        \App\Models\User::factory(9)->create();
        $this->call([
            ConditionsTableSeeder::class,
            CategorySeeder::class,
            ProductsTableSeeder::class,
            PaymentsTableSeeder::class,
        ]);
    }
}