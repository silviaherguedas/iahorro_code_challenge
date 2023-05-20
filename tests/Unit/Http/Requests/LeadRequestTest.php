<?php

namespace Tests\Unit\Http\Requests;

use App\Models\Client;
use App\Models\Lead;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
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
