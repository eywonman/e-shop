<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GuitarFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'price' => $this->faker->randomFloat(2, 100, 2000),
            'brand' => $this->faker->randomElement(['Fender', 'Gibson', 'Ibanez', 'Yamaha']),
            'image_url' => $this->faker->imageUrl(640, 480, 'music', true),
            'stock' => $this->faker->numberBetween(0, 30),
        ];
    }
}

