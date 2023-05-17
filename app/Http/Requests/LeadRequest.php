<?php

namespace App\Http\Requests;

class LeadRequest extends BaseFormRequest
{
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
        return [
            'score' => 'required|numeric|between:0.00,99.99|regex:/^\d+(\.\d{1,2})?$/'
        ];
    }
}
