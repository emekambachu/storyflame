<?php

namespace App\Http\Requests\Admin\ReferralType;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminStoreReferralType extends FormRequest
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
            'name' => ['required', 'string', 'unique:referral_types,name'],
            'slug' => ['required', 'string', 'unique:referral_types,slug'],
            'priority' => ['required', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string']
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
