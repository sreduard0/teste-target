<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'street' => fake()->streetName(),
            'number' => fake()->buildingNumber(),
            'neighborhood' => fake()->cityPrefix(),
            'complement' => fake()->secondaryAddress(),
            'zip_code' => fake()->postcode(),
        ];
    }
}