@extends('information_sheets.master')

@section('title', $definition['name'])

@section('content')

    {{-- Render Project Details component --}}
    @project_details(['data' => $data['project']]) @endproject_details

    {{-- Render Learning Product Details component --}}
    @learning_product_details(['data' => $data['course']]) @endlearning_product_details

    {{-- Render all process forms components --}}
    @design(['data' => $data['forms']['design'], 'learningProduct' => $data['course']]) @enddesign
    @design_assessment(['data' => $data['forms']['design-assessment']]) @enddesign_assessment

@endsection
