<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepository
{
    public function __construct(protected Client $client) {}

    /**
     * Save resource
     */
    public function create(array $data)
    {
        $client = $this->fill($data);

        $client->save();

        return $client->fresh();
    }

    /**
     * Update resource
     */
    public function update(array $data, int $id)
    {
        $client = $this->fill($data, $id);

        $client->update();

        return $client;
    }

    private function fill(array $data, int $id = null): Client
    {
        $client = (is_null($id)) ?
            new $this->client :
            $this->client->find($id);

        $client->name = $data['name'];
        $client->email = $data['email'];
        $client->phone = $data['phone'];

        return $client;
    }
}
