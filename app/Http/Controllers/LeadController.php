<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Requests\LeadPutRequest;
use App\Models\Lead;
use App\Services\ClientService;
use App\Services\LeadService;
use Exception;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    /**
     * @var array
     */
    private $errorNotFound = ['status' => 404, 'error' => 'Resource not found.'];

    public function __construct(
        protected LeadService $leadService,
        protected ClientService $clientService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->leadService->getAll();
        } catch (Exception $e) {
            $result = $this->response_status422($e->getMessage());
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : JsonResponse
    {
        return response()->json($this->errorNotFound, 404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request) : JsonResponse
    {
        $result = ['status' => 201];

        try {
            $client = $this->clientService->create($request->validated());
            $lead = $this->leadService->create($client);

            $result['data'] = ['client' => $client, 'lead' => $lead];
        } catch (Exception $e) {
            $result = $this->response_status422($e->getMessage());
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead): JsonResponse
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->leadService->getById($lead);
        } catch (Exception $e) {
            $result = $this->response_status422($e->getMessage());
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead): JsonResponse
    {
        return response()->json($this->errorNotFound, 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LeadPutRequest $request, Lead $lead): JsonResponse
    {
        $result = ['status' => 200];
        $validated_client = $request->safe()->only(['name', 'email', 'phone']);
        $validated_lead = array_merge($request->safe()->only(['score']), ['client_id' => $lead->client_id]);

        try {
            $client = $this->clientService->update($validated_client, $lead->client_id);
            $lead = $this->leadService->update($validated_lead, $lead->id);

            $result['data'] = ['client' => $client, 'lead' => $lead];
        } catch (Exception $e) {
            $result = $this->response_status422($e->getMessage());
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $result = ['status' => 204];

        try {
            $result['data'] = $this->leadService->delete($lead);
        } catch (Exception $e) {
            $result = $this->response_status422($e->getMessage());
        }
        return response()->json($result, $result['status']);
    }

    private function response_status422(string $message): array
    {
        return ['status' => 422, 'error' => $message];
    }
}
