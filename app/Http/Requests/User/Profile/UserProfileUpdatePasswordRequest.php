<?php

namespace App\Http\Requests\User\Profile;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserProfileUpdatePasswordRequest extends FormRequest
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
            'current_password' => 'nullable|string',
            'new_password' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'new_password.min' => 'Password must be at least 8 characters',
        ];
    }

    protected function failedValidation(Validator $validator){
        // return errors in json object/array
        $message = $validator->errors()->messages();
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $message
        ], 422));
    }
}
