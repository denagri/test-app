<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'sell_user_id' => User::factory(),
            'name'         => $this->faker->word(),
            'image_path'   => 'products/sample.png',
            'brand'        => $this->faker->company(),
            'price'        => $this->faker->numberBetween(500, 10000),
            'explanation'  => $this->faker->sentence(),
            'condition_id' => 1,
        ];
    }
}