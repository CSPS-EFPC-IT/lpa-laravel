<?php

namespace App\Http\Requests;

use App\Rules\AllowedForLearningProduct;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProcessInstanceFormDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Defer to ProcessInstanceFormRequest authorize method.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Return whether or not the form is being submitted.
     *
     * @return boolean
     */
    protected function submitted()
    {
        return collect($this->segments())->last() == 'submit';
    }

    /**
     * Retrieve entity tied to the current process instance.
     * i.e. Project, Course, etc.
     *
     * @return App\Models\BaseModel
     */
    protected function processEntity()
    {
        return $this->processInstanceFormData->processInstance->entity;
    }

    /**
     * Mark fields as invalid based on learning product type.
     *
     * @param  array|string $types
     * @return \Illuminate\Validation\Rules\RequiredIf
     */
    protected function allowedForTypes($types)
    {
        return new AllowedForLearningProduct(
            $this->processEntity(), $types
        );
    }

    /**
     * Mark fields as required based on learning product type.
     *
     * @param  array|string $types
     * @return \Illuminate\Validation\Rules\RequiredIf
     */
    protected function requiredForTypes($types)
    {
        return Rule::requiredIf(
            $this->processEntity()->isType($types)
        );
    }
}
