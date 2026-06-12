<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conditions =[
            ['kind' =>'良好'],
            ['kind' =>'目立った傷や汚れなし'],
            ['kind' =>'やや傷や汚れあり'],
            ['kind' =>'状態が悪い'],
        ];
        DB::table('conditions')->insert($conditions);
    }
}
