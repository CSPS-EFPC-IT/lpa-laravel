@form(['form' => $data])

    @php $formData = $data['form_data']; @endphp

    {{-- Description --}}
    @form_section(['title' => trans('forms/design.tabs.description')])
        <dl>
            @field(['data' => $formData['learning_product_code']['code'], 'field' => 'design.learning_product_code']) @endfield
            @field(['data' => $formData['sequence'], 'field' => 'design.sequence']) @endfield
            @field(['data' => $formData['version'], 'field' => 'design.version']) @endfield
            @field(['data' => $formData['description'], 'field' => 'design.description']) @endfield
            @field(['data' => $formData['learning_objectives'], 'field' => 'design.learning_objectives']) @endfield
            @field(['data' => $formData['target_audience_description'], 'field' => 'design.target_audience_description']) @endfield
            @field_list(['data' => $formData['outcome_types'], 'field' => 'design.outcome_types']) @endfield_list
            @field_boolean(['data' => $formData['is_required_training'], 'field' => 'design.is_required_training']) @endfield_boolean
            @field_duration(['data' => $formData['total_duration'], 'field' => 'design.total_duration']) @endfield_duration
        </dl>
    @endform_section

    {{-- Specifications --}}
    @form_section(['title' => trans('forms/design.tabs.specifications')])
        <dl>
            @is_learning_product($learningProduct, 'course.instructor-led')
                @field_list(['data' => $formData['possible_offering_types'], 'field' => 'design.possible_offering_types']) @endfield_list
            @endis_learning_product
            @field(['data' => $formData['registration_mode']['name'], 'field' => 'design.registration_mode']) @endfield
            @is_learning_product($learningProduct, 'course.online-self-paced')
                @field(['data' => $formData['expected_annual_participant_number'], 'field' => 'design.expected_annual_participant_number']) @endfield
            @endis_learning_product
            @is_learning_product($learningProduct, ['course.instructor-led', 'course.distance-learning'])
                <h4>{{ trans_choice('forms/design.form_section_groups.expected_annual_participants.label', 2) }}</h4>
                @forelse($formData['expected_annual_participants'] as $item)
                    @field_group(['field' => 'design.form_section_groups.expected_annual_participants.label', 'index' => $loop->iteration])
                        <dl>
                            @field(['data' => $item['region']['name'], 'field' => 'design.expected_annual_participants.region']) @endfield
                            @field(['data' => $item['expected_annual_participant_number'], 'field' => 'design.expected_annual_participants.number']) @endfield
                        </dl>
                    @endfield_group
                @empty
                    {{ trans('entities/general.none') }}
                @endforelse
                @field(['data' => $formData['expected_annual_participant_comments'], 'field' => 'design.expected_annual_participant_comments']) @endfield
            @endis_learning_product
        </dl>
    @endform_section

    {{-- Content --}}
    @form_section(['title' => trans('forms/design.tabs.content')])
        {{-- Applicable Policies --}}
        <h4>{{ trans_choice('forms/design.form_section_groups.applicable_policy.label', 2) }}</h4>
        @forelse($formData['applicable_policies'] as $item)
            @field_group(['field' => 'design.form_section_groups.applicable_policy.label', 'index' => $loop->iteration])
                <dl>
                    @field(['data' => $item['name'], 'field' => 'design.applicable_policies.applicable_policy']) @endfield
                </dl>
            @endfield_group
        @empty
            {{ trans('entities/general.none') }}
        @endforelse

        <dl>
            @field(['data' => $formData['content_source_type']['name'], 'field' => 'design.content_source_type']) @endfield
            @field_list(['data' => $formData['content_source_codes'], 'field' => 'design.content_source_codes', 'key' => 'code']) @endfield_list
        </dl>
    @endform_section

    {{-- Classifications --}}
    @form_section(['title' => trans('forms/design.tabs.classifications')])
        <dl>
            @field_list(['data' => $formData['topics'], 'field' => 'design.topics']) @endfield_list
            @field_list(['data' => $formData['programs'], 'field' => 'design.programs']) @endfield_list
            @field_list(['data' => $formData['series'], 'field' => 'design.series']) @endfield_list
            @field(['data' => $formData['curriculum_type']['name'], 'field' => 'design.curriculum_type']) @endfield
            @field_list(['data' => $formData['management_accountability_framework_areas'], 'field' => 'design.management_accountability_framework_areas']) @endfield_list
            @field_list(['data' => $formData['competencies'], 'field' => 'design.competencies']) @endfield_list
            @field(['data' => $formData['target_audience_knowledge_level']['name'], 'field' => 'design.target_audience_knowledge_level']) @endfield
            @field_list(['data' => $formData['communities'], 'field' => 'design.communities']) @endfield_list
        </dl>
    @endform_section

    {{-- Prerequisites --}}
    @form_section(['title' => trans('forms/design.tabs.prerequisites')])
        <dl>
            @field_list(['data' => $formData['mandatory_prerequisites'], 'field' => 'design.mandatory_prerequisites', 'key' => 'code']) @endfield_list
            @field_list(['data' => $formData['recommended_prerequisites'], 'field' => 'design.recommended_prerequisites', 'key' => 'code']) @endfield_list
            @field_list(['data' => $formData['complementary_learning_products'], 'field' => 'design.complementary_learning_products', 'key' => 'code']) @endfield_list
        </dl>
    @endform_section

    {{-- Clients --}}
    @form_section(['title' => trans('forms/design.tabs.clients')])
        <dl>
            @field_boolean(['data' => $formData['prescribed_by_tbs'], 'field' => 'design.prescribed_by_tbs']) @endfield_boolean
            @field_list(['data' => $formData['prescribed_by_departments'], 'field' => 'design.prescribed_by_departments']) @endfield_list
            @field_list(['data' => $formData['recommended_by_departments'], 'field' => 'design.recommended_by_departments']) @endfield_list
        </dl>
    @endform_section

    {{-- Timeframe --}}
    @form_section(['title' => trans('forms/design.tabs.timeframe')])
        <dl>
            @field_date(['data' => $formData['expected_pilot_start_date'], 'field' => 'design.expected_pilot_start_date']) @endfield_date
            @field_date(['data' => $formData['expected_launch_date'], 'field' => 'design.expected_launch_date']) @endfield_date
            @field(['data' => $formData['recommended_review_interval'], 'field' => 'design.recommended_review_interval']) @endfield
        </dl>
    @endform_section

    {{-- Costs --}}
    @form_section(['title' => trans('forms/design.tabs.costs')])
        {{-- Additional Costs --}}
        <h4>{{ trans_choice('forms/design.form_section_groups.additional_cost.label', 2) }}</h4>
        @forelse($formData['additional_costs'] as $item)
            @field_group(['field' => 'design.form_section_groups.additional_cost.label', 'index' => $loop->iteration])
                <dl>
                    @field(['data' => $item['rationale'], 'field' => 'design.additional_costs.rationale']) @endfield
                    @field(['data' => $item['costs'], 'field' => 'design.additional_costs.costs']) @endfield
                </dl>
            @endfield_group
        @empty
            {{ trans('entities/general.none') }}
        @endforelse

        <dl>
            @field(['data' => $formData['internal_order'], 'field' => 'design.internal_order']) @endfield
        </dl>
    @endform_section

    {{-- Comments --}}
    @form_section(['title' => trans('forms/design.tabs.comments')])
        <dl>
            @field(['data' => $formData['comments'], 'field' => 'design.comments']) @endfield
        </dl>
    @endform_section

@endform
