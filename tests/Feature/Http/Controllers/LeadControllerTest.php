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
        // dd($leads->toArray(), $client, $response->getContent());

        $response
            ->assertOk()
            ->assertJson([
                'status' => 200,
                'data' => [
                    [
                        'id' => $leads->id,
                        'score' => $leads->score,
                        'client' => $client
                        // [
                        //     'id' => $client['id'],
                        //     'name' => $client['name'],
                        //     'email' => $client['email'],
                        //     'phone' => $client['phone']
                        // ]
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
                'status' => 200,
                'data' => [
                    'id' => $lead->id,
                    'score' => $lead->score,
                    'client' => $client
                ]
            ]);
    }


}
