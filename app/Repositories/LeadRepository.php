<?php

namespace App\Repositories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Collection;

class LeadRepository implements RepositoryInterface
{

    public function __construct(protected Lead $model) {}

    /**
     * Get all resources.
     */
    public function getAll(): Collection
    {
        return $this->model
            ->with('client')
            ->get();
    }

    /**
     * Get resource by Id
     */
    public function getById(int $id): Lead
    {
        return $this->model
            ->with('client')
            ->find($id);
    }

    /**
     * Create resource
     */
    public function create(array $data): Lead
    {
        $model = $this->fill($data);

        $model->save();

        return $model->fresh();
    }

    /**
     * Update resource
     */
    public function update(array $data, int $id): Lead
    {
        $model = $this->fill($data, $id);

        $model->update();

        return $model;
    }

    /**
     * Delete resource
     */
    public function deleteById(int $id): Lead
    {
        $model = $this->model->find($id);
        $model->delete();

        return $model;
    }

    private function fill(array $data, int $id = null): Lead
    {
        $model = (is_null($id)) ?
            new $this->model :
            $this->model->find($id);

        $model->client_id = $data['client_id'];
        $model->score = $data['score'];

        return $model;
    }
}
