@form(['form' => $data])

    @php $formData = $data['form_data']; @endphp

    {{-- Instructors --}}
    @is_learning_product($learningProduct, ['course.instructor-led', 'course.distance-learning'])
        @form_section(['title' => trans('forms/operational_details.tabs.instructors')])
            <h4>{{ trans_choice('forms/operational_details.form_section_groups.instructor.label', 2) }}</h4>
            @forelse($formData['instructors'] as $item)
                @field_group(['field' => 'operational_details.form_section_groups.instructor.label', 'index' => $loop->iteration])
                    <dl>
                        @field_localizable(['data_en' => $item['required_profile_en'], 'data_fr' => $item['required_profile_fr'], 'field' => 'operational_details.instructors.required_profile']) @endfield_localizable
                        @field_localizable(['data_en' => $item['schedule_en'], 'data_fr' => $item['schedule_fr'], 'field' => 'operational_details.instructors.schedule']) @endfield_localizable
                    </dl>
                @endfield_group
            @empty
                {{ trans('entities/general.none') }}
            @endforelse
        @endform_section
    @endis_learning_product

    {{-- Guest Speakers --}}
    @is_learning_product($learningProduct, 'course.instructor-led')
        @form_section(['title' => trans('forms/operational_details.tabs.guest_speakers')])
            <h4>{{ trans_choice('forms/operational_details.form_section_groups.guest_speaker.label', 2) }}</h4>
            @forelse($formData['guest_speakers'] as $item)
                @field_group(['field' => 'operational_details.form_section_groups.guest_speaker.label', 'index' => $loop->iteration])
                    <dl>
                        @field_localizable(['data_en' => $item['required_profile_en'], 'data_fr' => $item['required_profile_fr'], 'field' => 'operational_details.instructors.required_profile']) @endfield_localizable
                        @field_localizable(['data_en' => $item['schedule_en'], 'data_fr' => $item['schedule_fr'], 'field' => 'operational_details.instructors.schedule']) @endfield_localizable
                    </dl>
                @endfield_group
            @empty
                {{ trans('entities/general.none') }}
            @endforelse
        @endform_section
    @endis_learning_product

    {{-- Offering Details --}}
    @is_learning_product($learningProduct, ['course.instructor-led', 'course.distance-learning'])
        @form_section(['title' => trans('forms/operational_details.tabs.offering_details')])
        <dl>
            @is_learning_product($learningProduct, 'course.instructor-led')
                @field(['data' => $formData['number_of_virtual_producers_per_offering'], 'field' => 'operational_details.number_of_virtual_producers_per_offering']) @endfield
            @endis_learning_product

            @field(['data' => $formData['minimum_number_of_participant_per_offering'], 'field' => 'operational_details.minimum_number_of_participant_per_offering']) @endfield
            @field(['data' => $formData['maximum_number_of_participant_per_offering'], 'field' => 'operational_details.maximum_number_of_participant_per_offering']) @endfield
            @field(['data' => $formData['optimal_number_of_participant_per_offering'], 'field' => 'operational_details.optimal_number_of_participant_per_offering']) @endfield
            @field(['data' => $formData['waiting_list_maximum_size'], 'field' => 'operational_details.waiting_list_maximum_size']) @endfield
            @field(['data' => $formData['session_template'], 'field' => 'operational_details.session_template']) @endfield

            @is_learning_product($learningProduct, 'course.instructor-led')
                @field_boolean(['data' => $formData['external_delivery'], 'field' => 'operational_details.external_delivery']) @endfield_boolean
            @endis_learning_product
        </dl>
        @endform_section
    @endis_learning_product

    {{-- Rooms --}}
    @is_learning_product($learningProduct, 'course.instructor-led')
        @form_section(['title' => trans('forms/operational_details.tabs.rooms')])
            <h4>{{ trans_choice('forms/operational_details.form_section_groups.room.label', 2) }}</h4>
            @forelse($formData['rooms'] as $item)
                @field_group(['field' => 'operational_details.form_section_groups.room.label', 'index' => $loop->iteration])
                    <dl>
                        @field(['data' => $item['quantity'], 'field' => 'operational_details.rooms.quantity']) @endfield
                        @field(['data' => $item['room_usage']['name'], 'field' => 'operational_details.rooms.room_usage']) @endfield
                        @field(['data' => $item['room_type']['name'], 'field' => 'operational_details.rooms.room_type']) @endfield
                        @field(['data' => $item['room_layout']['name'], 'field' => 'operational_details.rooms.room_layout']) @endfield
                        @field_localizable(['data_en' => $item['room_layout_other_en'], 'data_fr' => $item['room_layout_other_fr'], 'field' => 'operational_details.rooms.room_layout_other']) @endfield_localizable
                        @field_localizable(['data_en' => $item['special_requirement_description_en'], 'data_fr' => $item['special_requirement_description_fr'], 'field' => 'operational_details.rooms.special_requirement_description']) @endfield_localizable
                    </dl>
                @endfield_group
            @empty
                {{ trans('entities/general.none') }}
            @endforelse
        @endform_section
    @endis_learning_product

    {{-- Materials --}}
    @is_learning_product($learningProduct, 'course.instructor-led')
        @form_section(['title' => trans('forms/operational_details.tabs.materials')])
            @if(empty($formData['materials']))
                {{ trans('entities/general.none') }}
            @else
                @table
                    @section('colgroup')
                        <colgroup>
                            {{-- specify only the columns that requires widths --}}
                            <col width="120">
                            <col width="auto">
                            <col width="auto">
                            <col width="auto">
                            <col width="120">
                        </colgroup>
                    @overwrite
                    @section('thead')
                        @table_cell(['tag' => 'th', 'field' => 'operational_details.materials.quantity']) @endtable_cell
                        @table_cell(['tag' => 'th', 'field' => 'operational_details.materials.material_item']) @endtable_cell
                        @table_cell(['tag' => 'th', 'field' => 'operational_details.materials.material_denominator']) @endtable_cell
                        @table_cell(['tag' => 'th', 'field' => 'operational_details.materials.material_location']) @endtable_cell
                        @table_cell(['tag' => 'th', 'field' => 'operational_details.materials.reusable']) @endtable_cell
                        @table_cell(['tag' => 'th', 'field' => 'operational_details.materials.notes']) @endtable_cell
                    @overwrite
                    @section('tbody')
                        @forelse($formData['materials'] as $item)
                            @table_row
                                @table_cell(['tag' => 'td', 'data' => $item['quantity']]) @endtable_cell
                                @if (isset($item['material_item']['name']))
                                    @table_cell(['tag' => 'td', 'data' => $item['material_item']['name']]) @endtable_cell
                                @else
                                    @table_cell(['tag' => 'td', 'data' => $item["material_item_other_$lang"]]) @endtable_cell
                                @endif
                                @table_cell(['tag' => 'td', 'data' => $item['material_denominator']['name']]) @endtable_cell
                                @table_cell(['tag' => 'td', 'data' => $item['material_location']['name']]) @endtable_cell
                                @table_cell(['tag' => 'td', 'data' => $item['reusable'], 'type' => 'boolean']) @endtable_cell
                                @table_cell(['tag' => 'td', 'data' => $item["notes_$lang"]]) @endtable_cell
                            @endtable_row
                        @empty
                            @table_row
                                @table_cell(['tag' => 'td']) @endtable_cell
                            @endtable_row
                        @endforelse
                    @overwrite
                @endtable
            @endif
        @endform_section
    @endis_learning_product

    {{-- Documents --}}
    @is_learning_product($learningProduct, ['course.instructor-led', 'course.distance-learning'])
        @form_section(['title' => trans('forms/operational_details.tabs.documents')])
            @forelse($formData['documents'] as $item)
                <div class="el-table-wrap">
                    @table
                        @section('colgroup')
                            <colgroup>
                                {{-- specify only the columns that requires widths --}}
                                <col width="auto">
                                <col width="100">
                                <col width="auto">
                                <col width="auto">
                                <col width="120">
                            </colgroup>
                        @overwrite
                        @section('thead')
                            @table_cell(['tag' => 'th', 'field' => 'operational_details.documents.document_category']) @endtable_cell
                            @table_cell(['tag' => 'th', 'field' => 'operational_details.documents.quantity']) @endtable_cell
                            @table_cell(['tag' => 'th', 'field' => 'operational_details.documents.document_denominator']) @endtable_cell
                            @table_cell(['tag' => 'th', 'field' => 'operational_details.documents.title']) @endtable_cell
                            @table_cell(['tag' => 'th', 'field' => 'operational_details.documents.version']) @endtable_cell
                            @table_cell(['tag' => 'th', 'field' => 'operational_details.documents.link', 'class' => 'link']) @endtable_cell
                            @table_cell(['tag' => 'th', 'field' => 'operational_details.documents.reusable', 'class' => 'reusable']) @endtable_cell
                        @overwrite
                        @section('tbody')
                            @table_row
                                @if (isset($item['document_category']['name']))
                                    @table_cell(['tag' => 'td', 'data' => $item['document_category']['name']]) @endtable_cell
                                @else
                                    @table_cell(['tag' => 'td', 'data' => $item["document_category_other_$lang"]]) @endtable_cell
                                @endif
                                @table_cell(['tag' => 'td', 'data' => $item['quantity']]) @endtable_cell
                                @table_cell(['tag' => 'td', 'data' => $item['document_denominator']['name']]) @endtable_cell
                                @table_cell(['tag' => 'td'])
                                    {{ trans('entities/form.en') }}
                                    {{ $item['title_en'] }}
                                    {{ trans('entities/form.fr') }}
                                    {{ $item['title_fr'] }}
                                @endtable_cell
                                @table_cell(['tag' => 'td', 'data' => $item['version']]) @endtable_cell
                                @table_cell(['tag' => 'td', 'class' => 'link'])
                                    {{ trans('entities/form.en') }}
                                    @link(['href' => $item['link_en']])
                                        {{ $item['link_en'] }}
                                    @endlink
                                    {{ trans('entities/form.fr') }}
                                    @link(['href' => $item['link_fr']])
                                        {{ $item['link_fr'] }}
                                    @endlink
                                @endtable_cell
                                @table_cell(['tag' => 'td', 'class' => 'reusable', 'data' => $item['reusable'], 'type' => 'boolean']) @endtable_cell
                            @endtable_row
                        @overwrite
                    @endtable
                    @table
                        @section('colgroup')
                            <colgroup>
                                {{-- specify only the columns that requires widths --}}
                                <col width="250">
                            </colgroup>
                        @overwrite
                        @section('thead') @overwrite
                        @section('tbody')
                            @table_row
                                @table_cell(['tag' => 'th', 'field' => 'operational_details.documents.printing_specifications']) @endtable_cell
                                @table_cell(['tag' => 'td', 'data' => $item["printing_specifications_$lang"]]) @endtable_cell
                            @endtable_row
                            @table_row
                                @table_cell(['tag' => 'th', 'field' => 'operational_details.documents.notes']) @endtable_cell
                                @table_cell(['tag' => 'td', 'data' => $item["notes_$lang"]]) @endtable_cell
                            @endtable_row
                        @overwrite
                    @endtable
                </div>
            @empty
                {{ trans('entities/general.none') }}
            @endforelse
        @endform_section
    @endis_learning_product

     {{-- GCcampus --}}
    @form_section(['title' => trans('forms/operational_details.tabs.gc_campus')])
        <dl>
            @field_boolean(['data' => $formData['should_be_published'], 'field' => 'operational_details.should_be_published']) @endfield_boolean
            @field(['data' => $formData['summary_content'], 'field' => 'operational_details.summary_content']) @endfield
            @field(['data' => $formData['note_content'], 'field' => 'operational_details.note_content']) @endfield
            @field(['data' => $formData['disclaimer_content'], 'field' => 'operational_details.disclaimer_content']) @endfield
        </dl>
    @endform_section

    {{-- Comments --}}
    @form_section(['title' => trans('forms/operational_details.tabs.comments')])
        <dl>
            @field(['data' => $formData['comments'], 'field' => 'operational_details.comments']) @endfield
        </dl>
    @endform_section

@endform
