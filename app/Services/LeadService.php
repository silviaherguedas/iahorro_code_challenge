<?php

namespace App\Services;

use App\Models\Lead;
use App\Repositories\LeadRepository;
use Illuminate\Database\Eloquent\Collection;

class LeadService
{
    /**
     * @var LeadRepository
     */
    protected $leadRepository;

    public function __construct(LeadRepository $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    /**
     * Get all resources
     */
    public function getAll(): Collection
    {
        return $this->leadRepository->getAll();
    }

    /**
     * Get resource by Id
     */
    public function getById(Lead $lead): Collection
    {
        return $this->leadRepository->getById($lead);
    }
}
