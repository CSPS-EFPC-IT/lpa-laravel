<?php

namespace App\Policies;

use App\Exceptions\InsufficientPrivilegesException;
use App\Exceptions\OperationDeniedException;
use App\Models\LearningProduct\Design\Design;
use App\Models\LearningProduct\LearningProductCode;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LearningProductCodePolicy
{
    use HandlesAuthorization;

    /**
     * Get executed before each action.
     *
     * @param  User $user | Current user.
     * @param  string $ability | Current action (update, create, delete, etc.).
     * @return void
     */
    public function before($user, $ability)
    {
        // Prevent any actions if user is not an admin.
        if (! $user->isAdmin()) {
            throw new InsufficientPrivilegesException();
        }
    }

    /**
     * Determine whether the user can create learning product codes.
     *
     * @param User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the learning product codes.
     *
     * @param  User $user
     * @param  LearningProductCode $learningProductCode
     * @return bool
     */
    public function update(User $user, LearningProductCode $learningProductCode)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the learning product code.
     *
     * @param  User $user
     * @param  LearningProductCode $learningProductCode
     * @return bool
     */
    public function delete(User $user, LearningProductCode $learningProductCode)
    {
        // Ensure that learning product code about to be deleted is not referenced
        // in any design form.
        $isLearningProductCodeUsed = function ($q) use ($learningProductCode) {
            $q->whereId($learningProductCode->id);
        };

        $referenced = Design::whereHas('learningProductCode', $isLearningProductCodeUsed)
            ->orWhereHas('contentSourceCodes', $isLearningProductCodeUsed)
            ->orWhereHas('mandatoryPrerequisites', $isLearningProductCodeUsed)
            ->orWhereHas('recommendedPrerequisites', $isLearningProductCodeUsed)
            ->orWhereHas('complementaryLearningProducts', $isLearningProductCodeUsed)
            ->count() > 0;

        if ($referenced) {
            throw new OperationDeniedException(trans('errors.learning_product_code.is_referenced'));
        }

        return true;
    }
}
