<?php

namespace App\Models\InformationSheet\Sheets\Course;

use App\Events\Process\CourseDevelopmentCompleted;
use App\Models\InformationSheet\InformationSheet;

class PreparationAndAdministration
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
        // Create preperation and administration information sheet if applicable.
        $course = $event->processInstance->entity;
        if (in_array($course->subType->name_key, ['instructor-led', 'distance-learning'])) {
            InformationSheet::createFromDefinition(
                $course,
                'preparation-and-administration'
            );
        }
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
            'state',
            'organizationalUnit',
            'primaryContact',
            'design.processInstanceForm',
            'operationalDetails.processInstanceForm',
            'communicationsMaterial.processInstanceForm',
        ]);

        return [
            'course'                  => $learningProduct->toArray(),
            'design'                  => $learningProduct->design->toArray(),
            'operational_details'     => $learningProduct->operationalDetails->toArray(),
            'communications_material' => $learningProduct->communicationsMaterial->toArray(),
            'audit'                   => $this->getAudit($learningProduct),
        ];
    }

    /**
     * Get most recent audit information between different forms used in the information sheet.
     *
     * @param  App\Models\LearningProduct\LearningProduct $learningProduct
     * @return array
     */
    protected function getAudit($learningProduct)
    {
        return collect([
            [
                'updated_at' => $learningProduct->design->processInstanceForm->updated_at,
                'updated_by' => $learningProduct->design->processInstanceForm->updatedBy->toArray(),
            ],
            [
                'updated_at' => $learningProduct->operationalDetails->processInstanceForm->updated_at,
                'updated_by' => $learningProduct->operationalDetails->processInstanceForm->updatedBy->toArray(),
            ],
            [
                'updated_at' => $learningProduct->communicationsMaterial->processInstanceForm->updated_at,
                'updated_by' => $learningProduct->communicationsMaterial->processInstanceForm->updatedBy->toArray(),
            ],
        ])
        ->sortByDesc('updated_at')
        ->first();
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
