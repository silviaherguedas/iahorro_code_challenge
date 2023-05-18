<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

class EloquentClientRepository implements RepositoryInterface
{
    public function getAll(): Collection
    {
        return Client::with('lead')->get();
    }

    public function getById(int $id): Client
    {
        return Client::with('lead')->find($id);
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function update(array $data, int $id): Client
    {
        return tap(Client::find($id))->update($data);
    }

    public function deleteById(int $id):bool
    {
        return Client::find($id)->delete();
    }
}
