<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randPhone = (bool) mt_rand(0, 1) ? $this->faker->phoneNumber() : null;

        return [
            'name' => $this->faker->firstName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $randPhone,
        ];
    }
}
