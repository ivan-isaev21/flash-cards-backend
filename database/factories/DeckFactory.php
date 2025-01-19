<?php

namespace Database\Factories;

use App\Application\Cards\Enums\DeckType;
use App\Application\Shared\Enums\Locale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deck>
 */
class DeckFactory extends Factory
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
            'name' => fake()->unique()->word(),
            'locale' => Locale::RU_RU->value,
            'type' => fake()->randomElement(DeckType::cases())->value,
            'created_by' => fake()->uuid()
        ];
    }
}
