<?php

namespace App\Models\Process;

use App\Events\ProcessInstanceFormClaimed;
use App\Events\ProcessInstanceFormSubmitted;
use App\Events\ProcessInstanceFormUnclaimed;
use App\Models\BaseModel;
use App\Models\OrganizationalUnit;
use App\Models\State;
use App\Models\Traits\UsesUserAudit;
use App\Models\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessInstanceForm extends BaseModel
{
    use SoftDeletes, UsesUserAudit;

    protected $fillable = [
        'entity_type',
        'entity_id',
        'process_form_id',
        'process_instance_id',
        'process_instance_step_id',
        'state_id',
        'organizational_unit_id',
        'current_editor',
        'engine_task_id',
        'created_by',
        'updated_by',
    ];

    protected $with = [
        'createdBy',
        'currentEditor',
        'definition',
        'organizationalUnit',
        'state',
        'updatedBy',
    ];

    public function definition()
    {
        return $this->belongsTo(ProcessForm::class, 'process_form_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function currentEditor()
    {
        return $this->belongsTo(User::class, 'current_editor');
    }

    public function organizationalUnit()
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    public function processInstance()
    {
        return $this->belongsTo(ProcessInstance::class);
    }

    public function step()
    {
        return $this->belongsTo(ProcessInstanceStep::class, 'process_instance_step_id');
    }

    public function formData()
    {
        return $this->morphTo('formData', 'entity_type', 'entity_id');
    }

    /**
     * Update current form editor.
     *
     * @param  mixed $user
     * @return $this
     */
    public function updateCurrentEditor($user)
    {
        if (is_null($user)) {
            $this->currentEditor()->dissociate();
        } else {
            $this->currentEditor()->associate($user);
        }

        $this->timestamps = false;
        $this->save();

        return $this;
    }

    /**
     * Assign user to be able to edit the form.
     *
     * @param  User $user
     * @return $this
     */
    public function claim(User $user = null)
    {
        $user = $user ?? auth()->user();
        $this->updateCurrentEditor($user);

        // Dispatch event for CamundaEventSubscriber to respond for.
        event(new ProcessInstanceFormClaimed($user, $this));

        return $this;
    }

    /**
     * Remove current form editor.
     *
     * @return $this
     */
    public function unclaim()
    {
        // Remove current editor.
        $this->updateCurrentEditor(null);

        // Dispatch event for CamundaEventSubscriber to respond for.
        event(new ProcessInstanceFormUnclaimed($this));

        return $this;
    }

    /**
     * Remove current form editor.
     *
     * @return $this
     */
    public function release()
    {
        return $this->unclaim();
    }

    /**
     * Save form data changes and update all audit informations.
     *
     * @param  array $data
     * @return $this
     */
    public function saveForm($data)
    {
        // Update form data class.
        $this->formData->saveFormData($data);

        // Update process instance form audit and timestamps.
        $this->updateAudit();

        // Update process instance audit and timestamps.
        $processInstance = $this->processInstance->updateAudit();

        // Update entity audit and timestamps.
        entity($processInstance->entity_type, $processInstance->entity_id)->updateAudit();

        return $this;
    }

    /**
     * Submit form.
     *
     * @return $this
     */
    public function submit()
    {
        // Remove current editor.
        $this->updateCurrentEditor(null);

        // Dispatch event for CamundaEventSubscriber to respond for.
        event(new ProcessInstanceFormSubmitted($this));

        return $this;
    }

}
