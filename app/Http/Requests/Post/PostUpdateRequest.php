<?php

namespace App\Http\Requests\Post;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
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
            'post_id' => 'required|integer',
            'title' => 'nullable|string',
            'artist' => 'nullable|string',
            'type' => 'nullable|array',
            'genre' => 'nullable|array',
            'image_url' => 'nullable|string',
            'description' => 'nullable|string',
            'sale_id' => 'nullable',
            'price' => 'nullable',
            'quantity' => 'nullable',
            'auction_id' => 'nullable',
            'name' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'start_bid' => 'nullable',
        ];
    }
}
