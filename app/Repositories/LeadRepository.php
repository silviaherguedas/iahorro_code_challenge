<?php

namespace App\Repositories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Collection;

class LeadRepository
{
    /**
     * @var Lead
     */
    protected $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function getAll(): Collection
    {
        return $this->lead->get();
    }


    public function getById(Lead $lead): Collection
    {
        return $this->lead
            ->where('id', $lead->id)
            ->get();
    }
}
