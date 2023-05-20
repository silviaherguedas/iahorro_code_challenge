<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Client;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LeadControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $routePrefix = 'leads.';

    public function test_can_get_all_leads()
    {
        $leads = Lead::factory()
            ->for(Client::factory())
            ->create();
        $client = $leads->client()->get()->toArray()[0];

        $response = $this->getJson(route($this->routePrefix . 'index'));

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Lead List',
                'data' => [
                    [
                        'id' => $leads->id,
                        'score' => round($leads->score, 2),
                        'client' => $client
                    ]
                ]
            ]);
    }

    public function test_can_show_lead()
    {
        $lead = Lead::factory()
            ->for(Client::factory())
            ->create();
        $client = $lead->client()->get()->toArray()[0];

        $response = $this->getJson(route($this->routePrefix . 'show', $lead->id));

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Lead Show',
                'data' => [
                    'id' => $lead->id,
                    'score' => round($lead->score, 2),
                    'client' => $client
                ]
            ]);
    }


    public function test_can_store_a_lead()
    {
        $client = Client::factory()->make();

        $response = $this->postJson(
            route($this->routePrefix . 'store'),
            $client->toArray()
        );

        $response
            ->assertCreated()
            ->assertJsonCount(3);

        $this->assertDatabaseHas(
            'clients',
            $client->toArray()
        );
    }

    public function test_can_update_a_lead()
    {
        $client = Client::factory()->create();

        /* Current record */
        $existingLead = Lead::factory()
            ->for($client)
            ->create();

        /* new record */
        $newLead = Lead::factory()
            ->for($client)
            ->make();

        $newData = [
            'name' => $client->name,
            'email' => $client->email,
            'phone' => $client->phone,
            'score' => $newLead->score,
        ];

        $response = $this->putJson(
            route($this->routePrefix . 'update', $existingLead->id),
            $newData
        );

        $response
            ->assertOk()
            ->assertJson([
            'data' => [
                'id' => $existingLead->id,
                'score' => $newData['score']
            ]
        ]);

        $this->assertDatabaseHas(
            'leads',
            $newLead->toArray()
        );
    }

    public function test_can_delete_a_lead()
    {
        $existingLead = Lead::factory()->create();

        $this->deleteJson(
            route($this->routePrefix . 'destroy', $existingLead)
        )->assertNoContent();

        $this->assertDatabaseMissing(
            'leads',
            $existingLead->toArray()
        );
    }
}
