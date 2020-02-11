@extends('information_sheets.master')

@section('title', $definition['name'])

@section('content')

@cardbox
    <dl>
        <div class="field-section">
            {{-- Course fields --}}
            @field_lpa_number(['data' => $data['course']['id']]) @endfield_lpa_number
            @field(['data' => $data['communications_material']["title_$lang"], 'field' => 'communications_material.title']) @endfield
            @field_learning_product_type(['data' => $data['course']]) @endfield_learning_product_type
            @field(['data' => $data['course']['state']['name'], 'label' => trans('entities/general.status')]) @endfield
            @field(['data' => $data['course']['organizational_unit']['name'], 'label' => trans_choice('entities/general.organizational_units', 1)]) @endfield
            @field(['data' => $data['course']['primary_contact']['name'], 'label' => trans('entities/learning_product.primary_contact')]) @endfield
            @field_audit(['data' => $data['audit'], 'type' => 'updated']) @endfield_audit

            {{-- Design fields --}}
            @php $formData = $data['design']; @endphp
            @field(['data' => $formData['learning_product_code']['code'], 'field' => 'design.learning_product_code']) @endfield
            @field_duration(['data' => $formData['total_duration'], 'field' => 'design.total_duration']) @endfield_duration

            {{-- Operational Details fields --}}
            @php $formData = $data['operational_details']; @endphp
            @field(['data' => $formData['minimum_number_of_participant_per_offering'], 'field' => 'operational_details.minimum_number_of_participant_per_offering']) @endfield
            @field(['data' => $formData['maximum_number_of_participant_per_offering'], 'field' => 'operational_details.maximum_number_of_participant_per_offering']) @endfield
            @field(['data' => $formData['optimal_number_of_participant_per_offering'], 'field' => 'operational_details.optimal_number_of_participant_per_offering']) @endfield

            @field(['data' => $formData['comments'], 'field' => 'operational_details.comments']) @endfield
        </div>

        @is_learning_product($data['course'], 'course.instructor-led')
            @form_section(['title' => trans('forms/operational_details.tabs.rooms')])
                @forelse($formData['rooms'] as $item)
                    @field_group(['field' => 'operational_details.form_section_groups.room.label', 'index' => $loop->iteration])
                        <dl>
                            @field(['data' => $item['quantity'], 'field' => 'operational_details.rooms.quantity']) @endfield
                            @field(['data' => $item['room_usage']['name'], 'field' => 'operational_details.rooms.room_usage']) @endfield
                            @field(['data' => $item['room_type']['name'], 'field' => 'operational_details.rooms.room_type']) @endfield
                            @field(['data' => $item['room_layout']['name'], 'field' => 'operational_details.rooms.room_layout']) @endfield
                            @field(['data' => $item["room_layout_other_$lang"], 'field' => 'operational_details.rooms.room_layout_other']) @endfield
                            @field(['data' => $item["special_requirement_description_$lang"], 'field' => 'operational_details.rooms.special_requirement_description']) @endfield
                        </dl>
                    @endfield_group
                @empty
                    {{ trans('entities/general.none') }}
                @endforelse
            @endform_section
        @endis_learning_product

        @is_learning_product($data['course'], 'course.instructor-led')
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
    </dl>
@endcardbox
@endsection
