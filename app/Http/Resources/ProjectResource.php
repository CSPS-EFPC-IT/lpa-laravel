<?php

namespace App\Http\Resources;

use App\Models\LearningProduct\LearningProduct;
use App\Models\Process\ProcessDefinition;
use App\Models\Process\ProcessInstance;

class ProjectResource extends BaseResource
{
    public function context($request)
    {
        // Load learning products associated with project.
        $learningProducts = LearningProduct::with([
            'type', 'subType', 'state', 'organizationalUnit', 'currentProcess.definition', 'updatedBy'
        ])
        ->whereProjectId($this->id)
        ->get();

        // Load information sheets associated with project.
        $informationSheets = $this->informationSheets;

        // Load process definitions associated with project.
        $processDefinitions = ProcessDefinition::getFor(entity_class('project'));

        // Load process instances associated with project.
        $processInstances = ProcessInstance::with([
            'definition', 'state', 'createdBy', 'updatedBy'
        ])
        ->where('entity_type', 'project')
        ->where('entity_id', $this->id)
        ->get();

        return [
            'learning_products'   => $learningProducts,
            'information_sheets'  => $informationSheets,
            'process_definitions' => $processDefinitions,
            'process_instances'   => $processInstances,
        ];
    }
}
