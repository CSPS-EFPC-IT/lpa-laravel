<?php

namespace App\Http\Requests;

use App\Models\LearningProduct\LearningProductCode;
use App\Models\Lists\ContentSourceType;

class DesignFormRequest extends ProcessInstanceFormDataRequest
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
                'learning_product_code_id'                                          => 'required|integer',
                'sequence'                                                          => 'required|integer|between:1,99',
                'version'                                                           => 'required|integer|between:1,99',
                'description'                                                       => 'required|string|max:1000',
                'learning_objectives'                                               => 'required|string|max:1250',
                'target_audience_description'                                       => 'required|string|max:1250',
                'outcome_types'                                                     => 'required|array',
                'is_required_training'                                              => 'required|boolean',
                'total_duration'                                                    => 'required|integer|between:1,810000',
                'possible_offering_types'                                           => 'array',
                'registration_mode_id'                                              => 'required|integer',
                'expected_annual_participant_number'                                => ['nullable', 'integer', 'between:1,500000', $this->requiredForTypes(['course.online-self-paced'])],
                'expected_annual_participant_comments'                              => 'nullable|string|max:1250',
                'expected_annual_participants'                                      => ['array', $this->requiredForTypes(['course.instructor-led', 'course.distance-learning'])],
                'expected_annual_participants.*.region_id'                          => 'required|integer|distinct',
                'expected_annual_participants.*.expected_annual_participant_number' => 'required|integer|between:1,500000',
                'applicable_policies'                                               => 'array',
                'applicable_policies.*.name'                                        => 'required|string|max:255',
                'content_source_type_id'                                            => 'required|integer',
                'content_source_codes'                                              => 'array',
                'topics'                                                            => 'required|array',
                'programs'                                                          => 'array',
                'series'                                                            => 'array',
                'curriculum_type_id'                                                => 'required|integer',
                'management_accountability_framework_areas'                         => 'required|array',
                'competencies'                                                      => 'array|max:3',
                'target_audience_knowledge_level_id'                                => 'required|integer',
                'communities'                                                       => 'required|array',
                'mandatory_prerequisites'                                           => 'array',
                'recommended_prerequisites'                                         => 'array',
                'complementary_learning_products'                                   => 'array',
                'prescribed_by_tbs'                                                 => 'required|boolean',
                'prescribed_by_departments'                                         => 'array',
                'recommended_by_departments'                                        => 'array',
                'expected_pilot_start_date'                                         => 'nullable|before_or_equal:expected_launch_date',
                'expected_launch_date'                                              => 'required|after_or_equal:expected_launch_date',
                'recommended_review_interval'                                       => 'required|integer|between:1,60',
                'additional_costs'                                                  => 'array',
                'additional_costs.*.rationale'                                      => 'required|string|max:1250',
                'additional_costs.*.costs'                                          => 'required|integer|between:0,999999',
                'internal_order'                                                    => 'nullable|string|max:7',
                'comments'                                                          => 'nullable|string|max:2500',
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

                // Ensure learning product codes are active.
                $this->validateLearningProductCodes();

                // Ensure there is at least one possible offering type when course is instructor-led.
                $this->validatePossibleOfferingTypes();

                // Ensure there is at least one content source code if learning product is not new.
                $this->validateContentSourceCodes();

                // Ensure all prerequisites learning product codes are distinct among themsevles.
                $this->validatePrerequisites();

                // Ensure no departments are provided if learning product was prescribed by TBS.
                $this->validateDepartmentsFields();
            });
        }
    }

    /**
     * Ensure learning product codes are still active upon form submission.
     *
     * @return void
     */
    protected function validateLearningProductCodes()
    {
        $learningProductCodeIsActive = function ($field) {
            // Normalize field value to array since some fields have multiple values.
            $value = $this->{$field};
            if (! is_array($value)) {
                $value = [$value];
            }
            // Validate each code and flag them as invalid if they are no longer active.
            foreach ($value as $codeId) {
                $learningProductCode = LearningProductCode::whereId($codeId)->first();
                if (! $learningProductCode->active) {
                    $this->validator->errors()->add(
                        $field, trans('validation.custom.learning_product_code.inactive', ['code' => $learningProductCode->code])
                    );
                }
            }
        };

        $learningProductCodeIsActive('learning_product_code_id');
        $learningProductCodeIsActive('mandatory_prerequisites');
        $learningProductCodeIsActive('complementary_learning_products');
    }

    /**
     * Ensure there is at least one possible offering type when course is instructor-led.
     *
     * @return void
     */
    protected function validatePossibleOfferingTypes()
    {
        // Get learning product tied to the process instance.
        $learningProduct = $this->processEntity();

        if (empty($this->possible_offering_types) && $learningProduct->isType('course.instructor-led')) {
            $this->validator->errors()->add(
                'possible_offering_types',
                trans('validation.required', ['attribute' => trans('forms/design.possible_offering_types.label')])
            );
        }
    }

    /**
     * Ensure there is at least one content source code if learning product is not new.
     *
     * @return void
     */
    protected function validateContentSourceCodes()
    {
        if (empty($this->content_source_codes) && ContentSourceType::find($this->content_source_type_id)->name_key !== 'new') {
            $this->validator->errors()->add(
                'content_source_codes',
                trans('validation.required', ['attribute' => trans('forms/design.content_source_codes.label')])
            );
        }
    }

    /**
     * Validate prerequisites fields by making sure they all have distinct values among themselves.
     *
     * @return void
     */
    protected function validatePrerequisites()
    {
        $uniquePrerequisites = function ($field1, $field2) {
            // When the two fields are equal, flag them as invalid.
            if (collect($this->{$field1})->diff($this->{$field2})->count() !== count($this->{$field1})) {
                $this->validator->errors()->add($field1, trans('validation.unique_prerequisites', ['attribute' => trans("forms/design.$field1.label")]));
                $this->validator->errors()->add($field2, trans('validation.unique_prerequisites', ['attribute' => trans("forms/design.$field2.label")]));
            }
        };

        $uniquePrerequisites('mandatory_prerequisites', 'recommended_prerequisites');
        $uniquePrerequisites('mandatory_prerequisites', 'complementary_learning_products');
        $uniquePrerequisites('recommended_prerequisites', 'complementary_learning_products');
    }

    /**
     * Ensure no departments are provided if learning product was prescribed by TBS.
     *
     * @return void
     */
    protected function validateDepartmentsFields()
    {
        if ($this->prescribed_by_tbs) {
            if (! empty($this->prescribed_by_departments)) {
                $this->validator->errors()->add(
                    'prescribed_by_departments',
                    trans('validation.custom.prescribed_by_tbs', ['attribute' => trans('forms/design.prescribed_by_departments.label')])
                );
            }
            if (! empty($this->recommended_by_departments)) {
                $this->validator->errors()->add(
                    'recommended_by_departments',
                    trans('validation.custom.prescribed_by_tbs', ['attribute' => trans('forms/design.recommended_by_departments.label')])
                );
            }
        }
    }
}
