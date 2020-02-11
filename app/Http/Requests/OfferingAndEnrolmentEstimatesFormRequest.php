<?php

namespace App\Http\Requests;

class OfferingAndEnrolmentEstimatesFormRequest extends ProcessInstanceFormDataRequest
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
                'offering_regions'                                                               => 'required|array|min:1',
                'offering_regions.*.region_id'                                                   => 'required|integer|distinct',
                'offering_regions.*.regional_annual_bilingual_offering_number'                   => 'required|integer|between:0,365',
                'offering_regions.*.regional_annual_english_offering_number'                     => 'required|integer|between:0,365',
                'offering_regions.*.regional_annual_french_offering_number'                      => 'required|integer|between:0,365',
                'offering_regions.*.regional_annual_simultaneous_interpretation_offering_number' => ['nullable', 'integer', 'between:0,365', $this->requiredForTypes(['course.instructor-led'])],
                'comments'                                                                       => 'nullable|string|max:2500',
            ];
        }

        return [];
    }
}
