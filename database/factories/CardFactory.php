<?php

namespace Database\Factories;

use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Card::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(4),
            'rate' => 250,
            'min' => 10,
            'max' => 100,
            'type' => $this->faker->randomElement(['sell', 'buy']),
            'terms' => '<b> A test card</b>'
        ];
    }
}
