<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePartsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'description' => 'sometimes|string',
            'sellPrice' => 'sometimes|numeric',
            'purchasePrice' => 'sometimes|numeric',
            'quantity' => 'sometimes|numeric',
            'admin_id' => 'sometimes|numeric|exists:admins,id',
        ];
    }
}
