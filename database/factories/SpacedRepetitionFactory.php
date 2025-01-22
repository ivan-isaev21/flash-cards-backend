<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SpacedRepetition>
 */
class SpacedRepetitionFactory extends Factory
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
            'deck_item_id' => fake()->uuid(),
            'user_id' => fake()->uuid(),
            'easiness' => fake()->randomElement([1.3, 1.5, 1.7, 2, 2.5, 3]),
            'repetition' => rand(0, 4),
            'interval' => rand(1, 7),
            'next_date' => now()
        ];
    }
}
