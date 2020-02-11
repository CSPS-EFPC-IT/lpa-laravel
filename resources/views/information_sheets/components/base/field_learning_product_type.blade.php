@php $learningProductType = $data['type']['name'] . ($data['sub_type'] ? ' / ' . $data['sub_type']['name'] : ''); @endphp
@field(['data' => $learningProductType, 'label' => trans('entities/learning_product.type')]) @endfield
