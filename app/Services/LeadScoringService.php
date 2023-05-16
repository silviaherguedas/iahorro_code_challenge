<?php

namespace App\Services;

use App\Repositories\LeadRepository;


class LeadScoringService
{
    const MIN = 0;
    const MAX = 100;
    const ROUND = 2;

    protected $leadRepository;

    public function __construct(LeadRepository $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    public function getLeadScore()
    {
        return $this->float_rand(self::MIN, self::MAX, self::ROUND);
    }

    /**
     * Generate Float Random Number
     */
    private function float_rand(float $min, float $max, int $round = 0): float
    {
        if ($min > $max) {
            $aux = $min;
            $min = $max;
            $max = $aux;
        }

        $randomFloat = $min + mt_rand() / mt_getrandmax() * ($max - $min);

        if ($round > 0)
            $randomFloat = round($randomFloat, $round);

        return $randomFloat;
    }
}
