<?php

namespace App\Http\Requests\Admin\Summary;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminStoreSummaryRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:summaries,name'],
            'example_summary' => ['required', 'string'],
            'location' => ['required', 'string'],
            'purpose' => ['required', 'string'],
            'creation_prompt' => ['required', 'string'],
            'categories' => ['nullable', 'array'],
            'data_points' => ['nullable', 'array'],
            'summaries' => ['nullable', 'array'],
            'publish_at' => ['nullable', 'string'],
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
