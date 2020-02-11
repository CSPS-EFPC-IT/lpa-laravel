<?php

namespace App\Http\Requests;

use App\Models\Process\ProcessFormDecision;
use Illuminate\Validation\Rule;

class ProcessInstanceFormAssessmentRequest extends ProcessInstanceFormDataRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->submitted()) {
            // Prevent entity cancellation if not allowed.
            $cancelEntityOptions = $this->processInstanceFormData->formData->allow_entity_cancellation ? [true, false] : [false];

            return [
                'assessment_date'                        => 'required|date|before_or_equal:today',
                'is_entity_cancelled'                    => ['required', 'boolean', Rule::in($cancelEntityOptions)],
                'entity_cancellation_rationale'          => 'required_if:is_entity_cancelled,true|string|nullable|max:2500',
                'assessments.*.process_form_decision_id' => 'required_if:is_entity_cancelled,false|nullable|integer',
                'assessments.*.comments'                 => 'required_if:assessments.*.process_form_decision_id,' . ProcessFormDecision::getByKey('rejected')->first()->id,
            ];
        }

        return [];
    }
}
