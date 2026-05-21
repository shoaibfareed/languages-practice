<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(),
            'content' => fake()->sentence(),
            'locale' => fake()->randomElement([
                'en',
                'fr',
                'es'
            ])
        ];
    }
}
