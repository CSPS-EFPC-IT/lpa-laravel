@form(['form' => $data])

    @php $formData = $data['form_data']; @endphp

    {{-- Official Languages --}}
    @form_section(['title' => trans('forms/communications_material.tabs.official_languages')])
        <dl>
            @field_boolean(['data' => $formData['linguistic_services_consulted'], 'field' => 'communications_material.linguistic_services_consulted']) @endfield_boolean
            @field_language(['data' => $formData['material_original_language'], 'field' => 'communications_material.material_original_language']) @endfield_language
        </dl>
    @endform_section

    {{-- Titles --}}
    @form_section(['title' => trans('forms/communications_material.tabs.titles')])
        <dl>
            @field_localizable(['data_en' => $formData['title_en'], 'data_fr' => $formData['title_fr'], 'field' => 'communications_material.title']) @endfield_localizable
        </dl>
    @endform_section

    {{-- Summary --}}
    @form_section(['title' => trans('forms/communications_material.tabs.summary')])
        <dl>
            @field_localizable(['data_en' => $formData['summary_en'], 'data_fr' => $formData['summary_fr'], 'field' => 'communications_material.summary']) @endfield_localizable
        </dl>
    @endform_section

    {{-- Descriptions --}}
    @form_section(['title' => trans('forms/communications_material.tabs.descriptions')])
        <dl>
            @field_localizable(['data_en' => $formData['description_en'], 'data_fr' => $formData['description_fr'], 'field' => 'communications_material.description']) @endfield_localizable
        </dl>
    @endform_section

    {{-- Keywords --}}
    @form_section(['title' => trans('forms/communications_material.tabs.keywords')])
        <dl>
            @field_localizable(['data_en' => $formData['keywords_en'], 'data_fr' => $formData['keywords_fr'], 'field' => 'communications_material.keywords']) @endfield_localizable
        </dl>
    @endform_section

    {{-- Note --}}
    @form_section(['title' => trans('forms/communications_material.tabs.note')])
        <dl>
            @field_localizable(['data_en' => $formData['note_en'], 'data_fr' => $formData['note_fr'], 'field' => 'communications_material.note']) @endfield_localizable
        </dl>
    @endform_section

    {{-- Disclaimer --}}
    @form_section(['title' => trans('forms/communications_material.tabs.disclaimer')])
        <dl>
            @field_localizable(['data_en' => $formData['disclaimer_en'], 'data_fr' => $formData['disclaimer_fr'], 'field' => 'communications_material.disclaimer']) @endfield_localizable
        </dl>
    @endform_section

    {{-- Comments --}}
    @form_section(['title' => trans('forms/communications_material.tabs.comments')])
        <dl>
            @field(['data' => $formData['comments'], 'field' => 'communications_material.comments']) @endfield_localizable
        </dl>
    @endform_section

@endform
