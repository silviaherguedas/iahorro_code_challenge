<?php

namespace App\Repositories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Collection;

class EloquentLeadRepository implements RepositoryInterface
{
    public function getAll(): Collection
    {
        return Lead::with('client')->get();
    }

    public function getById(int $id): Lead
    {
        return Lead::with('client')->find($id);
    }

    public function create(array $data): Lead
    {
        return Lead::create($data);
    }

    public function update(array $data, int $id): Lead
    {
        return tap(Lead::find($id))->update($data);
    }

    public function deleteById(int $id): bool
    {
        return Lead::find($id)->delete();
    }
}
