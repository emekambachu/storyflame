<?php

namespace App\Http\Requests\User\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserVerifySignOnRequest extends FormRequest
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
            'email_token' => ['required', 'string', 'max:6'],
            'referral_code' => ['nullable', 'string', 'max:8'],
        ];
    }

    protected function failedValidation(Validator $validator){
        // return errors in json object/array
        //$message = $validator->errors()->all();
        $message = $validator->errors()->getMessages();
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $message
        ], 422));
    }

}
