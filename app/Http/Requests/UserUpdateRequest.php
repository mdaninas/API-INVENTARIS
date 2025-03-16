<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateRequest extends FormRequest
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
     */public function rules(): array
    {
        $userId = Auth::id();
        return [
            'fullname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $userId],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $userId],
            'password' => ['string', 'min:8'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048']
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(response()->json([
            'message' => 'Validasi gagal',
            'errors' => $errors
        ], 422));
    }
}
