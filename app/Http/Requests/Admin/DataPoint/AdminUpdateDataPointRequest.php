<?php

namespace App\Http\Requests\Admin\DataPoint;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'name' => ['required', 'string', 'unique:data_points,name', $itemId],
            'type' => ['required', 'string'],
            'development_order' => ['required', 'integer'],
            'impact_score' => ['required', 'integer'],
            'extraction_description' => ['required', 'string'],
            'example' => ['required', 'string'],
            'purpose' => ['required', 'string'],
            'publish_at' => ['required', 'string'],

            'categories' => ['required', 'array'],
            'achievements' => ['required', 'array'],
            'summaries' => ['required', 'array'],
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
