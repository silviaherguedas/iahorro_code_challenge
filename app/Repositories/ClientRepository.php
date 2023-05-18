<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

class ClientRepository implements RepositoryInterface
{
    public function __construct(protected Client $model) {}

    /**
     * Get all resources.
     */
    public function getAll(): Collection
    {
        return $this->model
            ->with('lead')
            ->get();
    }

    /**
     * Get resource by Id
     */
    public function getById(int $id): Client
    {
        return $this->model
            ->with('lead')
            ->find($id);
    }
    /**
     * Create resource
     */
    public function create(array $data): Client
    {
        $model = $this->fill($data);

        $model->save();

        return $model->fresh();
    }

    /**
     * Update resource
     */
    public function update(array $data, int $id): Client
    {
        $model = $this->fill($data, $id);

        $model->update();

        return $model;
    }
    /**
     * Delete resource
     */
    public function deleteById(int $id): Client
    {
        $model = $this->model->find($id);
        $model->delete();

        return $model;
    }

    private function fill(array $data, int $id = null): Client
    {
        $model = (is_null($id)) ?
            new $this->model :
            $this->model->find($id);

        $model->name = $data['name'];
        $model->email = $data['email'];
        $model->phone = $data['phone'];

        return $model;
    }
}
