<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\LeadAndClientRequest;
use App\Http\Requests\LeadPutRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Lead;
use App\Services\ClientService;
use App\Services\LeadService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LeadController extends Controller
{
    const ERROR_NOT_FOUND = 'Resource not found.';

    public function __construct(
        protected LeadService $leadService,
        protected ClientService $clientService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $result = $this->leadService->getAll();
            return (new ApiResponse('Lead List', $result, Response::HTTP_OK, true))->getJsonPayload();
        } catch (Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        return (new ApiResponse(self::ERROR_NOT_FOUND, null, Response::HTTP_NOT_FOUND, false))->getJsonPayload();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeadAndClientRequest $request): JsonResponse
    {
        try {
            $client = $this->clientService->create($request->validated());
            $lead = $this->leadService->create($client);

            $result = array_merge($lead->toArray(), ['client' => $client]);
            return (new ApiResponse('Lead stored successfully', $result, Response::HTTP_CREATED, true))->getJsonPayload();
        } catch (Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead): JsonResponse
    {
        try {
            $result = $this->leadService->getById($lead->id);
            return (new ApiResponse('Lead Show', $result, Response::HTTP_OK, true))->getJsonPayload();
        } catch (Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead): JsonResponse
    {
        return (new ApiResponse(self::ERROR_NOT_FOUND, null, Response::HTTP_NOT_FOUND, false))->getJsonPayload();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LeadPutRequest $request, Lead $lead): JsonResponse
    {
        $validated_client = $request->safe()->only(['name', 'email', 'phone']);
        $validated_lead = array_merge($request->safe()->only(['score']), ['client_id' => $lead->client_id]);

        try {
            $client = $this->clientService->update($validated_client, $lead->client_id);
            $lead = $this->leadService->update($validated_lead, $lead->id);

            $result = array_merge($lead->toArray(), ['client' => $client]);
            return (new ApiResponse('Lead updated successfully', $result, Response::HTTP_OK, true))->getJsonPayload();
        } catch (Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead): JsonResponse
    {
        try {
            $result = $this->leadService->deleteById($lead->id);
            return (new ApiResponse('Lead deleted successfully', $result, Response::HTTP_NO_CONTENT, true))->getJsonPayload();
        } catch (Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }
}
