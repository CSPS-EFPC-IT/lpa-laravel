<?php

namespace App\Listeners;

use App\Models\Process\ProcessInstance;
use App\Models\Process\ProcessInstanceForm;
use App\Models\Process\ProcessInstanceStep;

class ProcessEventSubscriber
{
    /**
     * Handle ProcessEntityUpdated events.
     * These events are fired whenever an entity that could have
     * process instances is being updated. (i.e. Project, LearningProduct, etc.).
     */
    public function onProcessEntityUpdated($event)
    {
        // Check if entity has a running process and its organizational unit was changed.
        $entity = $event->entity;
        if ($entity->isDirty('organizational_unit_id') && $entity->currentProcess) {
            $entity->currentProcess->forms->each(function($processInstanceForm) use ($entity) {
                if ($processInstanceForm->organizationalUnit && $processInstanceForm->organizationalUnit->owner) {
                    // Re-assign process instance form to its new organizational unit.
                    $processInstanceForm->organizationalUnit()->associate($entity->organizationalUnit);
                    $processInstanceForm->save();

                    // Remove any editor that can no longer edit the form.
                    if ($processInstanceForm->currentEditor && $processInstanceForm->currentEditor->cant('edit', $processInstanceForm)) {
                        $processInstanceForm->unclaim();
                    }
                }
            });
        }
    }

    /**
     * Handle ProcessEntityDeleted events.
     * These events are fired whenever an entity that could have
     * process instances is being deleted. (i.e. Project, LearningProduct, etc.).
     */
    public function onProcessEntityDeleted($event)
    {
        // Delete all process data associated with entity being deleted.
        $processInstanceIds = $event->entity->processInstances->pluck('id')->all();
        if (! empty($processInstanceIds)) {
            ProcessInstanceForm::whereIn('process_instance_id', $processInstanceIds)->delete();
            ProcessInstanceStep::whereIn('process_instance_id', $processInstanceIds)->delete();
            ProcessInstance::destroy($processInstanceIds);
        }
    }

    /**
     * Handle ProjectApprovalCompleted events.
     * These events are fired whenever a Project Approval process
     * is considered completed in Camunda.
     */
    public function onProcessProjectApprovalCompleted($event)
    {
        // Attach all form data entered during the process to the project entity.
        // (i.e. BusinessCase, PlannedProductList, etc.).
        \Process::load($event->processInstance)->attachFormData();
    }

    /**
     * Handle CourseDevelopmentCompleted events.
     * These events are fired whenever a Course Development process
     * is considered completed in Camunda.
     */
    public function onCourseDevelopmentCompleted($event)
    {
        // Attach all form data entered during the process to the course entity.
        // (i.e. Design, Operational Details, Communications Material etc.).
        \Process::load($event->processInstance)->attachFormData();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\ProcessEntityUpdated',
            'App\Listeners\ProcessEventSubscriber@onProcessEntityUpdated'
        );

        $events->listen(
            'App\Events\ProcessEntityDeleted',
            'App\Listeners\ProcessEventSubscriber@onProcessEntityDeleted'
        );

        $events->listen(
            'App\Events\Process\ProjectApprovalCompleted',
            'App\Listeners\ProcessEventSubscriber@onProcessProjectApprovalCompleted'
        );

        $events->listen(
            'App\Events\Process\CourseDevelopmentCompleted',
            'App\Listeners\ProcessEventSubscriber@onCourseDevelopmentCompleted'
        );
    }

}
