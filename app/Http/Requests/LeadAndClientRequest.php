<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class LeadAndClientRequest extends BaseFormRequest
{
    protected $client_id;

    public function __construct(Request $request)
    {
        $this->client_id = (int) optional($request->route("lead"))->client_id ?? 0;
    }

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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients,email,' . $this->client_id,
            'phone' => 'nullable|string|max:20|phone:ES',
        ];
    }

    /**
     * Get the filters that apply to the request data.
     */
    public function filters(): array
    {
        return [
            'name' => 'trim|escape|capitalize',
            'email' => 'trim|escape|lowercase',
            'phone' => 'trim|empty_string_to_null|escape',
        ];
    }
}
