@extends('information_sheets.master')

@section('title', $definition['name'])

@section('content')

    {{-- Render Project Details component --}}
    @project_details(['data' => $data['project']]) @endproject_details

    {{-- Render all process forms components --}}
    @business_case(['data' => $data['forms']['business-case']]) @endbusiness_case
    @planned_product_list(['data' => $data['forms']['planned-product-list']]) @endplanned_product_list
    @gate_one_approval(['data' => $data['forms']['gate-one-approval']]) @endgate_one_approval

@endsection
