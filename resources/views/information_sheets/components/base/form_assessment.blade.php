@form(['form' => $data])

    @php $formData = $data['form_data']; @endphp
    @php $entityCancelled = $formData['is_entity_cancelled'] ? 'true' : 'false'; @endphp

    {{-- Overall Assessment --}}
    @form_section(['title' => trans('forms/form_assessment.tabs.overall_assessment')])
        <dl>
            @field_date(['data' => $formData['assessment_date'], 'field' => 'form_assessment.assessment_date']) @endfield_date
            @if ($formData['allow_entity_cancellation'])
                @field(['data' => trans("forms/form_assessment.entity_cancellation.$entityType.is_entity_cancelled.options.$entityCancelled"), 'field' => "form_assessment.entity_cancellation.$entityType.is_entity_cancelled"]) @endfield
                @field(['data' => $formData['entity_cancellation_rationale'], 'field' => "form_assessment.entity_cancellation.$entityType.entity_cancellation_rationale"]) @endfield
            @endif
        </dl>
    @endform_section

    {{-- Forms Assessment --}}
    @foreach($formData['assessments'] as $assessment)
        @form_section(['title' => $assessment['assessed_process_form_definition']['name']])
            <dl>
                @field(['data' => $assessment['decision']['name'], 'field' => 'form_assessment.process_form_decision_id']) @endfield
                @field(['data' => $assessment['comments'], 'field' => 'form_assessment.assessment_comments']) @endfield
            </dl>
        @endform_section
    @endforeach

@endform
