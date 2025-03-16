<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemCreateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item_name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'max:500', 'unique:items,description'],
            'stock' => ['integer', 'min:0'],
            'price' => ['integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
        ];
    }
}
