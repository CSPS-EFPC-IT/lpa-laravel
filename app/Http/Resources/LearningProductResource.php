<?php

namespace App\Http\Resources;

use App\Models\Process\ProcessDefinition;
use App\Models\Process\ProcessInstance;

class LearningProductResource extends BaseResource
{
    public function context($request)
    {
        $learningProductType = $this->type->name_key;

        // Load information sheets associated with learning product.
        $informationSheets = $this->informationSheets;

        // Load process definitions associated with learning product type.
        $processDefinitions = ProcessDefinition::getFor(entity_class($learningProductType));

        // Load process instances associated with learning product.
        $processInstances = ProcessInstance::with([
            'definition', 'state', 'createdBy', 'updatedBy'
        ])
        ->where('entity_type', $learningProductType)
        ->where('entity_id', $this->id)
        ->get();

        return [
            'information_sheets'  => $informationSheets,
            'process_definitions' => $processDefinitions,
            'process_instances'   => $processInstances,
        ];
    }
}
