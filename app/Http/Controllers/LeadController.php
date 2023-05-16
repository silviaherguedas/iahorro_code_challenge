<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Services\ClientService;
use App\Services\LeadService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    /**
     * @var ClientService
     */
    private $clientService;

    /**
     * @var LeadService
     */
    private $leadService;

    private $errorNotFound = ['status' => 404, 'error' => 'Resource not found.'];

    public function __construct(LeadService $leadService, ClientService $clientService)
    {
        $this->clientService = $clientService;
        $this->leadService = $leadService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->leadService->getAll();
        } catch (Exception $e) {
            $result = $this->response_status500($e->getMessage());
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
    public function store(Request $request) : JsonResponse
    {
        // return response()->json([
        //     'data' => Lead::create($request->all())
        // ], 201);


        $client = $this->clientService->create($request->all());
        $order = $this->leadService->create($client['id'], $request->all());

        return response()->json(['status' => 201, 'message' => 'Object created.'], 201);
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
            $result = $this->response_status500($e->getMessage());
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
    public function update(Request $request, Lead $lead)
    {
        // return response()->json([
        //     'data' => tap($lead)->update($request->all())
        // ]);

        $order = $this->leadService->update($request->all(), $lead);

        return response()->json($order, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        // $lead->delete();
        // return response([], 204);


        $this->leadService->delete($lead);
        return response()->json(['status' => 204, 'message' => 'Object delete.'], 204);
    }

    private function response_status500(string $message)
    {
        return [
            'status' => 500,
            'error' => $message
        ];
    }
}
