<?php

namespace App\Http\Requests;

use App\Models\LearningProduct\LearningProductCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class LearningProductCodeFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Defer to LearningProductCodePolicy class to handle authorization.
        switch ($this->method()) {
            case 'POST':
                return Gate::authorize('create', LearningProductCode::class);

            case 'PUT':
                return Gate::authorize('update', LearningProductCode::findOrFail($this->learningProductCode));
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code'     => "required|max:15|unique:learning_product_codes,code,{$this->learningProductCode},id,deleted_at,NULL",
            'active'   => 'required|boolean',
            'comments' => 'string|nullable|max:255',
        ];
    }
}
