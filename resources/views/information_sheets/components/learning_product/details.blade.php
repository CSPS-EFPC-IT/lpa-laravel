@cardbox(['title' => trans('entities/learning_product.details') ])
    <dl>
        @field_lpa_number(['data' => $data['id']]) @endfield_lpa_number
        @field_learning_product_type(['data' => $data]) @endfield_learning_product_type
        @field(['data' => $data['name'], 'label' => trans('entities/learning_product.name')]) @endfield
        @field(['data' => $data['organizational_unit']['name'], 'label' => trans_choice('entities/general.organizational_units', 1)]) @endfield
        @field(['data' => $data['organizational_unit']['director']['name'], 'label' => trans('entities/organizational_unit.director')]) @endfield
        @field(['data' => $data['state']['name'], 'label' => trans('entities/general.status')]) @endfield
        @field(['data' => $data['manager']['name'], 'label' => trans('entities/learning_product.manager')]) @endfield
        @field(['data' => $data['primary_contact']['name'], 'label' => trans('entities/learning_product.primary_contact')]) @endfield
        @field_audit(['data' => $data, 'type' => 'created']) @endfield_audit
        @field_audit(['data' => $data, 'type' => 'updated']) @endfield_audit
    </dl>
@endcardbox
