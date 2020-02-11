@extends('information_sheets.master')

@section('title', $definition['name'])

@section('content')

    {{-- Render Learning Product Details component --}}
    @learning_product_details(['data' => $data['course']]) @endlearning_product_details

    {{-- Render design forms components --}}
    @design(['data' => $data['design'], 'learningProduct' => $data['course']]) @enddesign

    {{-- Render Operational Details forms components --}}
    @operational_details(['data' => $data['operational_details'], 'learningProduct' => $data['course']]) @endoperational_details

    {{-- Render Solution Development Details forms components --}}
    @solution_development(['data' => $data['solution_development'], 'learningProduct' => $data['course']]) @endsolution_development

    {{-- Render Offering and Enrolment Estimates Details forms components if applicable --}}
    @is_learning_product($data['course'], ['course.instructor-led', 'course.distance-learning'])
        @offering_and_enrolment_estimates(['data' => $data['offering_and_enrolment_estimates'], 'learningProduct' => $data['course']]) @endoffering_and_enrolment_estimates
    @endis_learning_product

    {{-- Render Communication Material Details forms components --}}
    @communications_material(['data' => $data['communications_material']]) @endcommunications_material

@endsection
