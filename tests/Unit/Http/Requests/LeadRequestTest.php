<?php

namespace Tests\Unit\Http\Requests;

use App\Models\Client;
use App\Models\Lead;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use Propaganistas\LaravelPhone\PhoneNumber;
use Tests\TestCase;

class LeadRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private string $routePrefix = 'leads.';

    public function test_score_is_required_value_null_does_not_pass_validation(): void
    {
        $validatedField = 'score';
        $brokenRule = null;
        $labelError = Lang::get('validation.required', ['attribute' => $validatedField]);

        $this->update_validation($validatedField, $brokenRule, $labelError);
    }

    public function test_score_within_range_with_3_decimal_does_not_pass_validation(): void
    {
        $validatedField = 'score';
        $brokenRule = $this->faker->randomFloat(3, 0, 99.99);
        $labelError = Lang::get('validation.regex', ['attribute' => $validatedField]);

        $this->update_validation($validatedField, $brokenRule, $labelError);
    }

    public function test_score_outside_range_with_2_decimal_does_not_pass_validation(): void
    {
        $validatedField = 'score';
        $brokenRule = $this->faker->randomFloat(2, 99.99, 150);
        $labelError = Lang::get(
            'validation.between.numeric',
            [
                'attribute' => $validatedField,
                'min' => '0.00',
                'max' => 99.99,
            ]);

        $this->update_validation($validatedField, $brokenRule, $labelError);
    }

    public function test_score_not_numeric_does_not_pass_validation(): void
    {
        $validatedField = 'score';
        $brokenRule = $this->faker->word();
        $labelErrors = [
            Lang::get('validation.regex', ['attribute' => $validatedField]),
            Lang::get('validation.numeric', ['attribute' => $validatedField])
        ];

        $this->update_validation($validatedField, $brokenRule, $labelErrors);
    }

    public function test_score_between_0_and_50_when_phone_is_not_null_does_not_pass_validation(): void
    {
        $validatedField = 'score';
        $brokenRule = $this->faker->numberBetween(0, 49.99);
        $labelError = Lang::get(
            'validation.between.numeric',
            [
                'attribute' => $validatedField,
                'min' => '50.00',
                'max' => 99.99,
            ]
        );

        $phone = new PhoneNumber('+34983000000', 'ES');
        $phone->formatE164();
        $phone = (string) $phone;

        $clientData = [
            'phone' => $phone,
            'email' => $this->faker->email(),
            'name' => $this->faker->name(),
        ];
        $client = Client::factory()->create($clientData);
        $clientData = $client->toArray();
        $existing = Lead::factory()->for($client)->create();
        $new = array_merge ($clientData, [$validatedField => $brokenRule]);

        $this
            ->putJson(
                route($this->routePrefix . 'update', $existing),
                $new
            )->assertUnprocessable()
            ->assertJsonFragment([$validatedField => [$labelError]]);
    }

    public function test_score_between_50_and_100_when_phone_is_null_does_not_pass_validation(): void
    {
        $validatedField = 'score';
        $brokenRule = $this->faker->numberBetween(50.00, 99.99);
        $labelError = Lang::get(
            'validation.between.numeric',
            [
                'attribute' => $validatedField,
                'min' => '0.00',
                'max' => 49.99,
            ]
        );

        $clientData = [
            'phone' => null,
            'email' => $this->faker->email(),
            'name' => $this->faker->name(),
        ];
        $client = Client::factory()->create($clientData);
        $clientData = $client->toArray();
        $existing = Lead::factory()->for($client)->create();
        $new = array_merge($clientData, [$validatedField => $brokenRule]);

        $this
            ->putJson(
                route($this->routePrefix . 'update', $existing),
                $new
            )->assertUnprocessable()
            ->assertJsonFragment([$validatedField => [$labelError]]);
    }

    private function update_validation(String $validatedField, String | null $brokenRule, String | array $labelErrors)
    {
        $client = Client::factory()->create();
        $existing = Lead::factory()->for($client)->create();
        $new = Lead::factory()->for($client)->make([
            $validatedField => $brokenRule
        ]);

        if(!is_array($labelErrors)) $labelErrors = [$labelErrors];

        $this
            ->putJson(
                route($this->routePrefix . 'update', $existing),
                $new->toArray()
            )->assertUnprocessable()
            ->assertJsonFragment([$validatedField => $labelErrors]);
    }
}
