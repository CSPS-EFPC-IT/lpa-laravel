<?php

namespace App\Http\Requests;

class SolutionDevelopmentFormRequest extends ProcessInstanceFormDataRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->submitted()) {
            return [
                'design_provider_id'                  => 'required_without:design_provider_other|nullable|integer',
                'design_provider_other'               => 'required_without:design_provider_id|nullable|string|max:100',
                'implementation_provider_id'          => 'required_without:implementation_provider_other|nullable|integer',
                'implementation_provider_other'       => 'required_without:implementation_provider_id|nullable|string|max:100',
                'effort_required'                     => 'required|integer|between:0,36000000',
                'language_content_qa_completed'       => 'required|boolean',
                'instructional_designer_qa_completed' => 'required|boolean',
                'functional_qa_completed'             => 'required|boolean',
                'accessibility_qa_completed'          => 'required|boolean',
                'client_acceptance_test_completed'    => 'required|boolean',
                'comments'                            => 'nullable|string|max:2500',
            ];
        }

        return [];
    }
}
