<?php

namespace App\Http\Controllers;

use App\Models\Process\ProcessDefinition;

class AuthorizationController extends APIController
{
    /**
     * Iterate over a list of actions and test whether the user
     * can execute or not the action on the entity.
     *
     * @param  App\Models\BaseModel $entity
     * @param  array $actions
     * @return array
     */
    protected function resolveAuthorizations($entity, $actions)
    {
        $authorizations = [];
        foreach ($actions as $action) {
            $authorizations[$action] = auth()->user()->can($action, $entity);
        }
        return $authorizations;
    }

    /**
     * Default callback used to test authorizations on one or many actions on an entity.
     *
     * @param  App\Models\BaseModel $entityClass
     * @param  int $entityId
     * @param  string $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function actions($entityClass, $entityId = null, $action = null)
    {
        // Load instance when an entity id is defined as route parameter.
        // Otherwise use entity model class.
        $entity = ! is_null($entityId) ? $entityClass->findOrFail($entityId) : $entityClass;
        // Use route parameter defined for action otherwise load them from querystring.
        $actions = ! is_null($action) ? [$action] : request()->get('actions');

        // Malformed request, no actions were provided.
        if (! $actions || ! is_array($actions)) {
            return $this->respondInvalidRequest('No actions were provided.');
        }

        return $this->respond(
            $this->resolveAuthorizations($entity, $actions)
        );
    }

    /**
     * Test authorization for an action on entity model class.
     * (i.e. Create action on project).
     *
     * @param  App\Models\BaseModel $entityClass
     * @param  string $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function entityAction($entityClass, $action)
    {
        return $this->respond(
            $this->resolveAuthorizations($entityClass, [$action])
        );
    }

    /**
     * Test authorization to start a given process definition on an entity.
     *
     * @param  App\Models\BaseModel $entityClass
     * @param  int $entityId
     * @param  ProcessDefinition $processDefinition
     * @return \Illuminate\Http\JsonResponse
     */
    public function startProcess($entityClass, $entityId, ProcessDefinition $processDefinition)
    {
        $entity = $entityClass->findOrFail($entityId);

        return $this->respond([
            'start-process' => auth()->user()->can('start-process', [$entity, $processDefinition])
        ]);
    }
}
