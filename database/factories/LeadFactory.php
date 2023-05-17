<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    protected $model = Lead::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'score' => function (array $attributes) {
                $phone = Client::find($attributes['client_id'])->phone;
                $min = ($phone === null) ? 0 : 50;
                $max = ($phone === null) ? 49.99 : 99.99;

                return $this->faker->randomFloat(2, $min, $max);
            },
        ];
    }
}
