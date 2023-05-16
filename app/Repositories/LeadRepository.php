<?php

namespace App\Repositories;

use App\Models\Lead;

class LeadRepository
{
    protected $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }
}
