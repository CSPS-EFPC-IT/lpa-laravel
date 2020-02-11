<?php

namespace App\Rules;

use App\Models\OrganizationalUnit;
use Illuminate\Contracts\Validation\Rule;

class UserBelongsToOrganizationalUnit implements Rule
{
    protected $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $this->attribute = $attribute;

        return auth()->user()->isAdmin() || auth()->user()->organizationalUnits->contains($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.in', [':attribute' => $this->attribute]);
    }
}
