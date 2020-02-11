<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AllowedForLearningProduct implements Rule
{
    protected $learningProduct;
    protected $allowedTypes;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($learningProduct, $types)
    {
        $this->learningProduct = $learningProduct;
        $this->allowedTypes = $types;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Valid if learning product correspond to types defined for the field.
        // Otherwise value submitted must be empty.
        $allowed = $this->learningProduct->isType($this->allowedTypes);
        return $allowed || (! $allowed && ! filled($value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.custom.learning_product.not_applicable');
    }
}
