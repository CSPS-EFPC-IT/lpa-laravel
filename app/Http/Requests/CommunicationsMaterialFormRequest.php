<?php

namespace App\Http\Requests;

use App\Models\LearningProduct\Development\OperationalDetails;

class CommunicationsMaterialFormRequest extends ProcessInstanceFormDataRequest
{
    protected $validator;
    protected $operationalDetails;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->submitted()) {
            return [
                'title_en'       => 'required|string|max:250',
                'title_fr'       => 'required|string|max:250',
                'summary_en'     => 'nullable|string|max:280',
                'summary_fr'     => 'nullable|string|max:280',
                'description_en' => 'required|string|max:1000',
                'description_fr' => 'required|string|max:1000',
                'keywords_en'    => 'required|string|max:500',
                'keywords_fr'    => 'required|string|max:500',
                'note_en'        => 'nullable|string|max:500',
                'note_fr'        => 'nullable|string|max:500',
                'disclaimer_en'  => 'nullable|string|max:500',
                'disclaimer_fr'  => 'nullable|string|max:500',
                'comments'       => 'nullable|string|max:2500',
            ];
        }

        return [];
    }

    /**
     * Inject additional custom validation rules.
     *
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        if ($this->submitted()) {
            $this->validator = $validator;
            $processInstanceId = $this->processInstanceFormData->process_instance_id;

            // Validate fields based on values previously entered in operational details form.
            $this->operationalDetails = OperationalDetails::whereProcessInstanceId($processInstanceId)->first();
            $this->validateSummary();
            $this->validateNote();
            $this->validateDisclaimer();
        }
    }

    /**
     * Ensure summary fields are filled if summary content was previously entered.
     *
     * @return void
     */
    protected function validateSummary()
    {
        $this->validator->sometimes(['summary_en', 'summary_fr'], 'required', function () {
            return ! empty($this->operationalDetails->summary_content);
        });
    }

    /**
     * Ensure note fields are filled if note content was previously entered.
     *
     * @return void
     */
    protected function validateNote()
    {
        $this->validator->sometimes(['note_en', 'note_fr'], 'required', function () {
            return ! empty($this->operationalDetails->note_content);
        });
    }

    /**
     * Ensure disclaimer fields are filled if disclaimer content was previously entered.
     *
     * @return void
     */
    protected function validateDisclaimer()
    {
        $this->validator->sometimes(['disclaimer_en', 'disclaimer_fr'], 'required', function () {
            return ! empty($this->operationalDetails->disclaimer_content);
        });
    }
}
