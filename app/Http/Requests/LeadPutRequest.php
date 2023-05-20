<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;

class LeadPutRequest extends BaseFormRequest
{
    public function __construct(
        protected LeadRequest $leadRequest,
        protected LeadAndClientRequest $clientRequest
    ) {}

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function withValidator(Validator $validator)
    {
        $validator->sometimes('score', 'between:0.00,49.99', function ($input) {
            return $input->phone == null;
        });

        $validator->sometimes('score', 'between:50.00,99.99', function ($input) {
            return $input->phone != null;
        });
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
