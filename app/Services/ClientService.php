<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\EloquentClientRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class ClientService
{
    public function __construct(protected EloquentClientRepository $clientRepository) {}

    /**
     * Validate data.
     * Store to DB if there are no errors.
     */
    public function create(array $validator): Client
    {
        return $this->clientRepository->create($validator);
    }

    /**
     * Update data
     * Store to DB if there are no errors.
     */
    public function update(array $validator, int $id)
    {
        DB::beginTransaction();

        try {
            $client = $this->clientRepository->update($validator, $id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update data');
        }

        DB::commit();

        return $client;
    }
}
