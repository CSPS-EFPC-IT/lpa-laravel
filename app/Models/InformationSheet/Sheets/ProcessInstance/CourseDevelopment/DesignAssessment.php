<?php

namespace App\Models\InformationSheet\Sheets\ProcessInstance\CourseDevelopment;

use App\Events\Process\CourseDevelopmentStarted;
use App\Models\InformationSheet\InformationSheet;
use App\Models\LearningProduct\Course;

class DesignAssessment
{
    /**
     * Respond to CourseDevelopmentStarted event to create and associate
     * an information sheet to the process instance.
     *
     * @param  CourseDevelopmentStarted $event
     * @return void
     */
    public function onCourseDevelopmentStarted($event)
    {
        // Create Design Assessment information sheet for process instance.
        InformationSheet::createFromDefinition(
            $event->processInstance, 'course-development.design-assessment'
        );
    }

    /**
     * Return data for a Design Assessment information sheet.
     *
     * @param  App\Models\Process\ProcessInstance $processInstance
     * @return array
     */
    public function getData($processInstance)
    {
        // Load course information.
        $course = $processInstance->entity->load('organizationalUnit.director', 'state', 'manager', 'primaryContact', 'createdBy', 'updatedBy');

        // Load parent project information.
        $project = $course->project->load('organizationalUnit.director', 'state', 'createdBy', 'updatedBy');

        // Load all process instance forms data.
        $forms = collect($processInstance->forms->each->load('formData'))->keyBy('entity_type');

        return [
            'course'  => $course->toArray(),
            'project' => $project->toArray(),
            'forms'   => $forms->toArray(),
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
            CourseDevelopmentStarted::class,
            static::class . '@onCourseDevelopmentStarted'
        );
    }
}
