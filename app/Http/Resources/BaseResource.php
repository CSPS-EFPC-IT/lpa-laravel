<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Retrieve context parameter from query string and cast as boolean.
        $shouldAddContext = filter_var($request->query('context', false), FILTER_VALIDATE_BOOLEAN);
        $context = $shouldAddContext === true ? $this->context($request) : [];

        return [
            'data' => parent::toArray($request),
            'meta' => [
                // Add resource context when required.
                $this->mergeWhen($shouldAddContext, $context)
            ]
        ];
    }

    /**
     * Here a resource can add additional context information
     * to the response meta attribute.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function context($request) {
        return [
            //
        ];
    }
}
