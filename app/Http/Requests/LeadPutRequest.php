<?php

namespace App\Http\Requests;

class LeadPutRequest extends BaseFormRequest
{
    public function __construct(
        protected LeadRequest $leadRequest,
        protected ClientRequest $clientRequest
    ) {}

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge($this->clientRequest->rules(), $this->leadRequest->rules());
    }

    /**
     * Get the filters that apply to the request data.
     */
    public function filters(): array
    {
        return array_merge($this->clientRequest->filters(), $this->leadRequest->filters());
    }
}
