@form(['form' => $data])

    @php $formData = $data['form_data']; @endphp

    {{-- Regions --}}
    @form_section(['title' => trans('forms/offering_and_enrolment_estimates.tabs.regions')])
        <h4>{{ trans_choice('forms/offering_and_enrolment_estimates.form_section_groups.region.label', 2) }}</h4>
        @forelse($formData['offering_regions'] as $item)
            @field_group(['field' => 'offering_and_enrolment_estimates.form_section_groups.region.label', 'index' => $loop->iteration])
                <dl>
                    @field(['data' => $item['region']['name'], 'field' => 'offering_and_enrolment_estimates.regions.region']) @endfield
                    @field(['data' => $item['regional_annual_bilingual_offering_number'], 'field' => 'offering_and_enrolment_estimates.regions.regional_annual_bilingual_offering_number']) @endfield
                    @field(['data' => $item['regional_annual_english_offering_number'], 'field' => 'offering_and_enrolment_estimates.regions.regional_annual_english_offering_number']) @endfield
                    @field(['data' => $item['regional_annual_french_offering_number'], 'field' => 'offering_and_enrolment_estimates.regions.regional_annual_french_offering_number']) @endfield
                    @is_learning_product($learningProduct, 'course.instructor-led')
                        @field(['data' => $item['regional_annual_simultaneous_interpretation_offering_number'], 'field' => 'offering_and_enrolment_estimates.regions.regional_annual_simultaneous_interpretation_offering_number']) @endfield
                    @endis_learning_product
                    @field(['data' => $item['regional_annual_total_offering_number'], 'field' => 'offering_and_enrolment_estimates.regions.regional_annual_total_offering_number']) @endfield
                </dl>
            @endfield_group
        @empty
            {{ trans('entities/general.none') }}
        @endforelse

        <dl>
            @field(['data' => $formData['national_annual_bilingual_offering_number'], 'field' => 'offering_and_enrolment_estimates.national_annual_bilingual_offering_number']) @endfield
            @field(['data' => $formData['national_annual_english_offering_number'], 'field' => 'offering_and_enrolment_estimates.national_annual_english_offering_number']) @endfield
            @field(['data' => $formData['national_annual_french_offering_number'], 'field' => 'offering_and_enrolment_estimates.national_annual_french_offering_number']) @endfield
            @is_learning_product($learningProduct, 'course.instructor-led')
                @field(['data' => $formData['national_annual_simultaneous_interpretation_offering_number'], 'field' => 'offering_and_enrolment_estimates.national_annual_simultaneous_interpretation_offering_number']) @endfield
            @endis_learning_product
            @field(['data' => $formData['national_annual_total_offering_number'], 'field' => 'offering_and_enrolment_estimates.national_annual_total_offering_number']) @endfield
        </dl>

    @endform_section

    {{-- Comments --}}
    @form_section(['title' => trans('forms/offering_and_enrolment_estimates.tabs.comments')])
        <dl>
            @field(['data' => $formData['comments'], 'field' => 'offering_and_enrolment_estimates.comments']) @endfield
        </dl>
    @endform_section

@endform
