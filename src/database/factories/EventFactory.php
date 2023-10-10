<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'description' => fake()->sentence(),
            'start' => now()->addDay()->format('c'),
            'end' => now()->addDay()->addHours(1)->format('c'),
            'frequency' => fake()->randomElement([null, 'daily', 'weekly', 'monthly', 'yearly']),
            'repeat_until' => now()->addDays(10)->format('c'),
        ];
    }
}
