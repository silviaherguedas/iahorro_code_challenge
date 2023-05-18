<?php

namespace Database\Factories;

use App\Models\Client;
use Faker\Factory as FakerFactory;
use Faker\Provider\es_ES\PhoneNumber;
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
        $this->faker = FakerFactory::create('es_ES');
        $this->faker->addProvider(new PhoneNumber($this->faker));

        return [
            'name' => $this->faker->firstName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->getRandPhone(),
        ];
    }

    private function getRandPhone()
    {
        return (bool) mt_rand(0, 1) ? $this->faker->tollFreeNumber() : null;
    }
}
