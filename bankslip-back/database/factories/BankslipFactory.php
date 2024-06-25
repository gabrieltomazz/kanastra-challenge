<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bankslip>
 */
class BankslipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'government_id' =>  Str::random(10),
            'email' => fake()->unique()->safeEmail(),
            'debt_amount' => 1000.00,
            'debt_due_date' => now(),
            'debt_id' =>  Str::random(10),
        ];
    }
}
