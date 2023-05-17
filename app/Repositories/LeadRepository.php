<?php

namespace App\Repositories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Collection;

class LeadRepository
{

    public function __construct(protected Lead $lead) {}

    /**
     * Get all resources.
     */
    public function getAll(): Collection
    {
        return $this->lead->get();
    }

    /**
     * Get resource by Id
     */
    public function getById(Lead $lead): Lead
    {
        return $this->lead
            ->where('id', $lead->id)
            ->first();
    }

    /**
     * Save resource
     */
    public function create(array $data)
    {
        $lead = $this->fill($data);

        $lead->save();

        return $lead->fresh();
    }

    /**
     * Update resource
     */
    public function update(array $data, int $id)
    {
        $lead = $this->fill($data, $id);

        $lead->update();

        return $lead;
    }

    /**
     * Delete resource
     */
    public function delete(Lead $data)
    {
        $lead = $this->lead->find($data->id);
        $lead->delete();

        return $lead;
    }

    private function fill(array $data, int $id = null): Lead
    {
        $lead = (is_null($id)) ?
            new $this->lead :
            $this->lead->find($id);

        $lead->client_id = $data['client_id'];
        $lead->score = $data['score'];

        return $lead;
    }
}
