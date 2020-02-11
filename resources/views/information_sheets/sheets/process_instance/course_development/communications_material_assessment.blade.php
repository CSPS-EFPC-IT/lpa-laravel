@extends('information_sheets.master')

@section('title', $definition['name'])

@section('content')

    {{-- Render Project Details component --}}
    @project_details(['data' => $data['project']]) @endproject_details

    {{-- Render Learning Product Details component --}}
    @learning_product_details(['data' => $data['course']]) @endlearning_product_details

    {{-- Render all process forms components --}}
    @communications_material(['data' => $data['forms']['communications-material']]) @endcommunications_material
    @communications_material_assessment(['data' => $data['forms']['communications-material-assessment']]) @endcommunications_material_assessment

@endsection
