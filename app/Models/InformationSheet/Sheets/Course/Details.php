<?php

namespace App\Models\InformationSheet\Sheets\Course;

use App\Events\Process\CourseDevelopmentCompleted;
use App\Models\InformationSheet\InformationSheet;
use App\Models\Process\ProcessInstanceForm;

class Details
{
    /**
     * Respond to CourseDevelopmentCompleted event to create and associate
     * an information sheet to the learning product.
     *
     * @param  CourseDevelopmentCompleted $event
     * @return void
     */
    public function onCourseDevelopmentCompleted($event)
    {
        // Create details information sheet for learning product.
        InformationSheet::createFromDefinition(
            $event->processInstance->entity,
            'details'
        );
    }

    /**
     * Return data for learning product details information sheet.
     *
     * @param  App\Models\LearningProduct\LearningProduct $learningProduct
     * @return array
     */
    public function getData($learningProduct)
    {
        // Load course information.
        $learningProduct->load([
            'organizationalUnit.director',
            'state',
            'manager',
            'primaryContact',
            'createdBy',
            'updatedBy',
        ]);

        // Load design form.
        $design = ProcessInstanceForm::where([
            'entity_type' => 'design',
            'entity_id'   => $learningProduct->design_id,
        ])->with('formData')->firstOrfail();

        // Load operational details form.
        $operationalDetails = ProcessInstanceForm::where([
            'entity_type' => 'operational-details',
            'entity_id'   => $learningProduct->operational_details_id,
        ])->with('formData')->firstOrfail();

        // Load solution development form.
        $solutionDevelopment = ProcessInstanceForm::where([
            'entity_type' => 'solution-development',
            'entity_id'   => $learningProduct->solution_development_id,
        ])->with('formData')->firstOrfail();

        // Load offering and enrolment estimates form if applicable.
        if (isset($learningProduct->offering_and_enrolment_estimates_id)) {
            $offeringAndEnrolmentEstimates = ProcessInstanceForm::where([
                'entity_type' => 'offering-and-enrolment-estimates',
                'entity_id'   => $learningProduct->offering_and_enrolment_estimates_id,
            ])->with('formData')->firstOrfail();
        }

        // Load communications material form.
        $communicationsMaterial = ProcessInstanceForm::where([
            'entity_type' => 'communications-material',
            'entity_id'   => $learningProduct->communications_material_id,
        ])->with('formData')->firstOrfail();

        return [
            'course'                            => $learningProduct->toArray(),
            'design'                            => $design->toArray(),
            'operational_details'               => $operationalDetails->toArray(),
            'solution_development'              => $solutionDevelopment->toArray(),
            'offering_and_enrolment_estimates'  => isset($offeringAndEnrolmentEstimates) ? $offeringAndEnrolmentEstimates->toArray() : null,
            'communications_material'           => $communicationsMaterial->toArray(),
        ];
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            CourseDevelopmentCompleted::class,
            static::class . '@onCourseDevelopmentCompleted'
        );
    }
}
