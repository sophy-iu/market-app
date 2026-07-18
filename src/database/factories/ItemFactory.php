<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{

    protected $model = Item::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'condition_id' => function () {
                return Condition::firstOrCreate([
                    'name' => '良好',
                ])->id;
            },
            'image' => 'items/test.jpg',
            'item_name' => $this->faker->word(),
            'brand_name' => $this->faker->company(),
            'price' => $this->faker->numberBetween(1000, 10000),
            'item_description' => $this->faker->sentence(),
        ];
    }
}
