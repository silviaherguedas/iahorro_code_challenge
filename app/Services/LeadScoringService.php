<?php

namespace App\Services;

use App\Repositories\LeadRepository;

/**
 * Service dedicated to calculating the score value for a given Lead.
 *
 * In this case and because we do not have the necessary business logic to be able to
 * perform a proper calculation, the tool will automatically generate a random number,
 * whose value is applied numerically between 0 (the lowest) and 100 (the highest score).
 * The higher the score, the more interesting the customer or lead is, as it means that
 * he or she is more likely to make a purchase.
 */
class LeadScoringService
{
    const MIN = 0;
    const MAX = 100;
    const ROUND = 2;

    /**
     * @var LeadRepository
     */
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
