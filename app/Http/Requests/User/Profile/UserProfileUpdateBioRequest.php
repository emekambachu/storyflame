<?php

namespace App\Http\Requests\User\Profile;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserProfileUpdateBioRequest extends FormRequest
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
    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'bio' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required!',
            'first_name.string' => 'First name must be a string!',
            'last_name.required' => 'Last name is required!',
            'last_name.string' => 'Last name must be a string!',
            'bio.required' => 'Bio is required!',
            'bio.string' => 'Bio must be a string!',
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
