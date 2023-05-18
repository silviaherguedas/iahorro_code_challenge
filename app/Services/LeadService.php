<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Lead;
use App\Repositories\LeadRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class LeadService
{
    public function __construct(
        protected LeadRepository $leadRepository
    ) {}

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
    public function getById(int $id): Lead
    {
        return $this->leadRepository->getById($id);
    }

    /**
     * Validate data.
     * Store to DB if there are no errors.
     */
    public function create(Client $client)
    {
        $data['client_id'] = $client->id;

        $leadScoringService = new LeadScoringService();
        $data['score'] = $leadScoringService->getLeadScore($client);

        $result = $this->leadRepository->create($data);

        return $result;
    }

    /**
     * Update data
     * Store to DB if there are no errors.
     */
    public function update(array $validator, int $id)
    {
        DB::beginTransaction();

        try {
            $lead = $this->leadRepository->update($validator, $id);
        } catch (Exception $e) {
            $this->rollBack($e->getMessage(), 'Unable to update data: ');
        }

        DB::commit();

        return $lead;
    }

    /**
     * Delete resource
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();

        try {
            $lead = $this->leadRepository->deleteById($id);
        } catch (Exception $e) {
            $this->rollBack($e->getMessage(), 'Unable to delete data: ');
        }

        DB::commit();

        return $lead;
    }

    private function rollBack(string $logMessage, string $message = '')
    {
        DB::rollBack();
        Log::info($logMessage);

        throw new InvalidArgumentException($message.$logMessage);
    }
}
