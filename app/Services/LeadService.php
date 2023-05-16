<?php

namespace App\Services;

use App\Repositories\LeadRepository;


class LeadService
{
    protected $leadRepository;

    public function __construct(LeadRepository $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }
}
