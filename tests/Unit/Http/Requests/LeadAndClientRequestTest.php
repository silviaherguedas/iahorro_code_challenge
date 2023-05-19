<?php

namespace Tests\Unit\Http\Requests;

use App\Models\Client;
use App\Models\Lead;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;
use Propaganistas\LaravelPhone\PhoneNumber;

class LeadAndClientRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private string $routePrefix = 'leads.';

    public function test_name_is_required_value_null_does_not_pass_validation(): void
    {
        $validatedField = 'name';
        $brokenRule = null;
        $labelError = $this->getLabelErrorRequired($validatedField);

        // Store validation
        $this->store_validation($validatedField, $brokenRule, $labelError);

        // Update validation
        $this->update_validation($validatedField, $brokenRule, $labelError);
    }

    public function test_name_exceed_255_characters_does_not_pass_validation(): void
    {
        $validatedField = 'name';
        $brokenRule = $this->faker->text(5000);
        $labelError = str_replace([':attribute', ':max'], [$validatedField, 255], Lang::get('validation.max.string'));;

        // Store validation
        $this->store_validation($validatedField, $brokenRule, $labelError);

        // Update validation
        $this->update_validation($validatedField, $brokenRule, $labelError);
    }

    public function test_email_is_required_value_null_does_not_pass_validation(): void
    {
        $validatedField = 'email';
        $brokenRule = null;
        $labelError = $this->getLabelErrorRequired($validatedField);

        // Store validation
        $this->store_validation($validatedField, $brokenRule, $labelError);

        // Update validation
        $this->update_validation($validatedField, $brokenRule, $labelError);
    }

    public function test_incorrectly_formatted_email_does_not_pass_validation(): void
    {
        $validatedField = 'email';
        $brokenRule = $this->faker->word();
        $labelError = str_replace(':attribute', $validatedField, Lang::get('validation.email'));

        // Store validation
        $this->store_validation($validatedField, $brokenRule, $labelError);

        // Update validation
        $this->update_validation($validatedField, $brokenRule, $labelError);
    }

    public function test_repeated_email_does_not_pass_validation(): void
    {
        $validatedField = 'email';
        $labelError = str_replace(':attribute', $validatedField, Lang::get('validation.unique'));

        $existing = Client::factory()->create();
        $this->store_validation($validatedField, $existing->email, $labelError);
    }

    public function test_phone_without_spain_format_does_not_pass_validation(): void
    {
        $validatedField = 'phone';
        $phone = new PhoneNumber('+3212/34.56.738', 'ES');
        $phone->formatE164();
        $brokenRule = (string) $phone;
        $labelError = $this->getLabelErrorPhone($validatedField);

        // Store validation
        $this->store_validation($validatedField, $brokenRule, $labelError);

        // Update validation
        $this->update_validation($validatedField, $brokenRule, $labelError);
    }

    public function test_phone_exceed_20_characters_does_not_pass_validation(): void
    {
        $validatedField = 'phone';
        $brokenRule = $this->faker->text(21);
        $labelError = $this->getLabelErrorPhone($validatedField);

        $client = Client::factory()->make();
        $client = $client->toArray();
        $client[$validatedField] = $brokenRule;

        $this
            ->postJson(
                route($this->routePrefix . 'store'),
                $client
            )->assertUnprocessable()
            ->assertJsonFragment([$validatedField => [$labelError]]);
    }

    private function store_validation(String $validatedField, String | null $brokenRule, String | array $labelErrors)
    {
        $client = Client::factory()->make([
            $validatedField => $brokenRule
        ]);

        if (!is_array($labelErrors)) $labelErrors = [$labelErrors];

        $this
            ->postJson(
                route($this->routePrefix . 'store'),
                $client->toArray()
            )->assertUnprocessable()
            ->assertJsonFragment([$validatedField => $labelErrors]);
    }

    private function update_validation(String $validatedField, String | null $brokenRule, String | array $labelErrors)
    {
        $client = Client::factory()->create();
        $existing = Lead::factory()->for($client)->create();

        $new = Lead::factory()->for($client)->make();
        $newClient = $client->toArray();
        $newClient[$validatedField] = $brokenRule;
        $newData = [
            'name' => $newClient['name'],
            'email' => $newClient['email'],
            'phone' => $newClient['phone'],
            'score' => $new->score,
        ];

        if (!is_array($labelErrors)) $labelErrors = [$labelErrors];

        $response = $this
            ->putJson(
                route($this->routePrefix . 'update', $existing),
                $newData
            )->assertUnprocessable()
            ->assertJsonFragment([$validatedField => $labelErrors]);
    }

    private function getLabelErrorRequired(String $validatedField): String
    {
        return str_replace(':attribute', $validatedField, Lang::get('validation.required'));
    }

    private function getLabelErrorPhone(String $validatedField): String
    {
        return str_replace(':attribute', $validatedField, Lang::get('validation.phone'));
    }
}
