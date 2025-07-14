<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GuitarFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'price' => $this->faker->randomFloat(2, 5000, 12000),
            'brand' => $this->faker->randomElement(['Fender', 'Gibson', 'Ibanez', 'Yamaha', 'Taylor', 'Martin', 'Aspire', 'Jcraft', 'Smiger']),
            'image_url' => $this->faker->imageUrl(640, 480, 'music', true),
            'stock' => $this->faker->numberBetween(0, 30),
        ];
    }
}

