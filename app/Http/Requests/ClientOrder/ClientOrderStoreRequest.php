<?php

namespace App\Http\Requests\ClientOrder;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ClientOrderStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'ordered_via' => 'required|string',
            'final_amount' => 'required|numeric',
            'payment' => 'required|string',
            'address' => 'required|string',
        ];
    }
}
