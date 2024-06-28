<?php

namespace App\Http\Requests\Admin\DataPoint;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class AdminUpdateDataPointRequest extends FormRequest
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
        $itemId = $this->route('item_id');
        return [
            'name' => ['required', 'string', Rule::unique('data_points', 'name')->ignore($itemId, 'item_id')],
            'type' => ['required', 'string'],
            'development_order' => ['required', 'integer'],
            'impact_score' => ['required'],
            'extraction_description' => ['required', 'string'],
            'example' => ['required', 'string'],
            'purpose' => ['required', 'string'],
            'estimated_seconds' => ['required', 'integer'],

            'categories' => ['required', 'array'],
            'achievement' => ['nullable', 'string'],
            'summaries' => ['nullable', 'array'],
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
