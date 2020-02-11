@extends('information_sheets.master')

@section('title', $definition['name'])

@section('content')

    {{-- Render Project Details component --}}
    @project_details(['data' => $data['project']]) @endproject_details

    {{-- Render Learning Product Details component --}}
    @learning_product_details(['data' => $data['course']]) @endlearning_product_details

    {{-- Render all process forms components --}}
    @operational_details(['data' => $data['forms']['operational-details'], 'learningProduct' => $data['course']]) @endoperational_details
    @solution_development(['data' => $data['forms']['solution-development'], 'learningProduct' => $data['course']]) @endsolution_development
    @is_learning_product($data['course'], ['course.instructor-led', 'course.distance-learning'])
        @offering_and_enrolment_estimates(['data' => $data['forms']['offering-and-enrolment-estimates'], 'learningProduct' => $data['course']]) @endoffering_and_enrolment_estimates
    @endis_learning_product
    @development_assessment(['data' => $data['forms']['development-assessment']]) @enddevelopment_assessment

@endsection
