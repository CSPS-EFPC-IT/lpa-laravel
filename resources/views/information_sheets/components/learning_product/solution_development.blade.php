@form(['form' => $data])

    @php $formData = $data['form_data']; @endphp

    {{-- Providers --}}
    @form_section(['title' => trans('forms/solution_development.tabs.providers')])
        <dl>
            @field(['data' => $formData['design_provider']['name'], 'field' => 'solution_development.design_provider']) @endfield
            @field(['data' => $formData['design_provider_other'], 'field' => 'solution_development.design_provider_other']) @endfield
            @field(['data' => $formData['implementation_provider']['name'], 'field' => 'solution_development.implementation_provider']) @endfield
            @field(['data' => $formData['implementation_provider_other'], 'field' => 'solution_development.implementation_provider_other']) @endfield
        </dl>
    @endform_section

    {{-- Effort --}}
    @form_section(['title' => trans('forms/solution_development.tabs.effort')])
        <dl>
            @field_duration(['data' => $formData['effort_required'], 'field' => 'solution_development.effort_required']) @endfield_duration
        </dl>
    @endform_section

    {{-- Quality Assurance --}}
    @form_section(['title' => trans('forms/solution_development.tabs.quality_assurance')])
        <dl>
            @field_boolean(['data' => $formData['language_content_qa_completed'], 'field' => 'solution_development.language_content_qa_completed']) @endfield_boolean
            @field_boolean(['data' => $formData['instructional_designer_qa_completed'], 'field' => 'solution_development.instructional_designer_qa_completed']) @endfield_boolean
            @field_boolean(['data' => $formData['functional_qa_completed'], 'field' => 'solution_development.functional_qa_completed']) @endfield_boolean
            @field_boolean(['data' => $formData['accessibility_qa_completed'], 'field' => 'solution_development.accessibility_qa_completed']) @endfield_boolean
            @field_boolean(['data' => $formData['client_acceptance_test_completed'], 'field' => 'solution_development.client_acceptance_test_completed']) @endfield_boolean
        </dl>
    @endform_section

    {{-- Comments --}}
    @form_section(['title' => trans('forms/solution_development.tabs.comments')])
        <dl>
            @field(['data' => $formData['comments'], 'field' => 'solution_development.comments']) @endfield
        </dl>
    @endform_section

@endform
