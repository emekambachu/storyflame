<?php

namespace App\Http\Requests\Admin\Achievement;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminStoreAchievementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:achievements,name'],
            'sub_title' => ['required', 'string'],
            'icon'  => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'color' => ['required', 'string'],
            'extraction_description' => ['required', 'string'],
            'example' => ['required', 'string'],
            'purpose' => ['required', 'string'],
            'publish_at' => ['required', 'date'],
            'categories' => ['required', 'array'],
            'data_points' => ['required', 'array'],
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
