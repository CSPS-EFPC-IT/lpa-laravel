<?php

namespace App\Http\Requests;

use App\Models\LearningProduct\Design\Design;

class OperationalDetailsFormRequest extends ProcessInstanceFormDataRequest
{
    protected $validator;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->submitted()) {
            return [
                'instructors'                                => ['array', $this->requiredForTypes(['course.instructor-led', 'course.distance-learning'])],
                'instructors.*.required_profile_en'          => 'required|string|max:1250',
                'instructors.*.required_profile_fr'          => 'required|string|max:1250',
                'instructors.*.schedule_en'                  => 'required|string|max:1250',
                'instructors.*.schedule_fr'                  => 'required|string|max:1250',
                'guest_speakers'                             => ['array', $this->allowedForTypes(['course.instructor-led'])],
                'guest_speakers.*.required_profile_en'       => 'required|string|max:1250',
                'guest_speakers.*.required_profile_fr'       => 'required|string|max:1250',
                'guest_speakers.*.schedule_en'               => 'required|string|max:1250',
                'guest_speakers.*.schedule_fr'               => 'required|string|max:1250',
                'number_of_virtual_producers_per_offering'   => 'nullable|integer|between:1,10',
                'minimum_number_of_participant_per_offering' => ['nullable', 'integer', 'between:1,100', $this->requiredForTypes(['course.instructor-led', 'course.distance-learning'])],
                'maximum_number_of_participant_per_offering' => ['nullable', 'integer', 'between:1,100', $this->requiredForTypes(['course.instructor-led', 'course.distance-learning'])],
                'optimal_number_of_participant_per_offering' => ['nullable', 'integer', 'between:1,100', $this->requiredForTypes(['course.instructor-led', 'course.distance-learning'])],
                'waiting_list_maximum_size'                  => ['nullable', 'integer', 'between:0,99', $this->requiredForTypes(['course.instructor-led', 'course.distance-learning'])],
                'session_template'                           => ['nullable', 'string', 'max:250', $this->requiredForTypes(['course.instructor-led', 'course.distance-learning'])],
                'external_delivery'                          => ['nullable', 'boolean', $this->requiredForTypes(['course.instructor-led'])],
                'rooms'                                      => ['array', $this->requiredForTypes(['course.instructor-led'])],
                'rooms.*.quantity'                           => 'required|integer|between:1,10',
                'rooms.*.room_usage_id'                      => 'required|integer',
                'rooms.*.room_type_id'                       => 'required|integer',
                'rooms.*.room_layout_id'                     => 'required_without:rooms.*.room_layout_other_en,rooms.*.room_layout_other_fr|nullable|integer',
                'rooms.*.room_layout_other_en'               => 'required_without:rooms.*.room_layout_id|nullable|string|max:100',
                'rooms.*.room_layout_other_fr'               => 'required_without:rooms.*.room_layout_id|nullable|string|max:100',
                'rooms.*.special_requirement_description_en' => 'required_with:rooms.*.special_requirement_description_fr|nullable|string|max:1250',
                'rooms.*.special_requirement_description_fr' => 'required_with:rooms.*.special_requirement_description_en|nullable|string|max:1250',
                'materials'                                  => ['array', $this->allowedForTypes(['course.instructor-led'])],
                'materials.*.quantity'                       => 'required|integer|between:1,100',
                'materials.*.material_item_id'               => 'required_without:materials.*.material_item_other_en,materials.*.material_item_other_fr|nullable|integer',
                'materials.*.material_item_other_en'         => 'required_without:materials.*.material_item_id|nullable|string|max:100',
                'materials.*.material_item_other_fr'         => 'required_without:materials.*.material_item_id|nullable|string|max:100',
                'materials.*.material_denominator_id'        => 'required|integer',
                'materials.*.material_location_id'           => 'required|integer',
                'materials.*.reusable'                       => 'required|boolean',
                'materials.*.notes_en'                       => 'required_with:materials.*.notes_fr|nullable|string|max:500',
                'materials.*.notes_fr'                       => 'required_with:materials.*.notes_en|nullable|string|max:500',
                'documents'                                  => ['array', $this->allowedForTypes(['course.instructor-led', 'course.distance-learning'])],
                'documents.*.quantity'                       => 'required|integer|between:1,100',
                'documents.*.document_category_id'           => 'required_without:documents.*.document_category_other_en,documents.*.document_category_other_fr|nullable|integer',
                'documents.*.document_category_other_en'     => 'required_without:documents.*.document_category_id|nullable|string|max:100',
                'documents.*.document_category_other_fr'     => 'required_without:documents.*.document_category_id|nullable|string|max:100',
                'documents.*.document_denominator_id'        => 'required|integer',
                'documents.*.title_en'                       => 'required|string|max:250',
                'documents.*.title_fr'                       => 'required|string|max:250',
                'documents.*.version'                        => 'required|string|max:10',
                'documents.*.link_en'                        => 'required|string|max:2048',
                'documents.*.link_fr'                        => 'required|string|max:2048',
                'documents.*.printing_specifications_en'     => 'required_with:documents.*.printing_specifications_fr|nullable|string|max:1250',
                'documents.*.printing_specifications_fr'     => 'required_with:documents.*.printing_specifications_en|nullable|string|max:1250',
                'documents.*.reusable'                       => 'required|boolean',
                'documents.*.notes_en'                       => 'required_with:documents.*.notes_fr|nullable|string|max:500',
                'documents.*.notes_fr'                       => 'required_with:documents.*.notes_en|nullable|string|max:500',
                'should_be_published'                        => 'required|boolean',
                'summary_content'                            => 'required_if:should_be_published,true|nullable|string|max:280',
                'note_content'                               => 'required_if:should_be_published,true|nullable|string|max:500',
                'disclaimer_content'                         => 'required_if:should_be_published,true|nullable|string|max:500',
                'comments'                                   => 'nullable|max:2500',
            ];
        }

        return [];
    }

    /**
     * Inject additional custom validation rules.
     *
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        if ($this->submitted()) {
            $validator->after(function ($validator) {
                $this->validator = $validator;

                // Ensure there is a number of virtual producers
                // when possible offering types is either "Virtual" or "Simultaneous Virtual and In-Person Classroom".
                $this->validateVirtualProducers();
            });
        }
    }

    /**
     * Ensure there is a number of virtual producers
     * when possible offering types is either "Virtual" or "Simultaneous Virtual and In-Person Classroom".
     *
     * @return void
     */
    protected function validateVirtualProducers()
    {
        // Rule is only valid for instructor-led.
        if (! $this->processEntity()->isType('course.instructor-led')) {
            return;
        }

        // Fetch design form data.
        if ($design = Design::whereProcessInstanceId($this->processInstanceFormData->process_instance_id)->first()) {
            // If possible offering types is one of these, ensure there is a number of virtual producers.
            if ($design->possibleOfferingTypes->whereIn('name_key', ['virtual', 'simultaneous-virtual-and-in-person'])->isNotEmpty()) {
                if (! isset($this->number_of_virtual_producers_per_offering)) {
                    $this->validator->errors()->add(
                        'number_of_virtual_producers_per_offering',
                        trans('validation.required', ['attribute' => trans('forms/operational_details.number_of_virtual_producers_per_offering.label')])
                    );
                }
            }
        }
    }
}
