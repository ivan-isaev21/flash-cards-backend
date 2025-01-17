<?php

namespace Database\Factories;

use App\Application\Shared\Enums\Locale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'locale' => Locale::RU_RU->value,
            'question' => fake()->word(),
            'answer' => fake()->text(),
            'keywords' => fake()->words(),
            'created_by' => fake()->uuid(),
        ];
    }
}
