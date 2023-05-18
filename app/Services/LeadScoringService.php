<?php

namespace App\Services;

use App\Models\Client;

/**
 * Service dedicated to the calculation of the score that will be assigned to a potential client,
 * applying the "Lead Scoring" methodology.
 *
 * Since we do not have the necessary business logic to be able to perform a proper calculation,
 * the tool will automatically generate a random number.
 * Whose value will be a number between 0 (the lowest) and 49.99, when the client does not provide
 * us with a telephone number; and between 50 and 99.99 (the highest score) when he does.
 *
 * The higher the score, the more interesting the customer is, as it means they are more likely
 * to make a purchase.
 */
class LeadScoringService
{
    const ROUND = 2;

    /**
     * It generates a score between 0 and 100, depending on whether or not we have a contact telephone number.
     */
    public function getLeadScore(Client $client)
    {
        $min = self::getMin($client->phone);
        $max = self::getMax($client->phone);

        return $this->float_rand($min, $max, self::ROUND);
    }

    public static function getMin(string $phone)
    {
        return ($phone === null) ? 0 : 50;
    }

    public static function getMax(string $phone)
    {
        return ($phone === null) ? 49.99 : 99.99;
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
