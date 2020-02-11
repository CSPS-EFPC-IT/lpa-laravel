<?php

namespace App\Models\LearningProduct;

use App\Events\LearningProductCreated;
use App\Events\LearningProductDeleted;
use App\Events\LearningProductUpdated;
use App\Models\BaseModel;
use App\Models\LearningProduct\Design\Design;
use App\Models\LearningProduct\Design\DesignAssessment;
use App\Models\LearningProduct\Development\OfferingAndEnrolmentEstimates;
use App\Models\LearningProduct\Development\OperationalDetails;
use App\Models\LearningProduct\Development\SolutionDevelopment;
use App\Models\LearningProduct\Development\DevelopmentAssessment;
use App\Models\LearningProduct\CommunicationsReview\CommunicationsMaterialAssessment;
use App\Models\LearningProduct\CommunicationsReview\CommunicationsMaterial;
use App\Models\Project\Project;
use App\Models\Traits\HasInformationSheets;
use App\Models\Traits\HasProcesses;
use App\Models\Traits\UsesUserAudit;
use App\Models\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

class LearningProduct extends BaseModel
{
    use SoftDeletes, UsesUserAudit, HasProcesses, SingleTableInheritanceTrait, HasInformationSheets;

    // Indicate which field is used to store the type of each class.
    protected static $singleTableTypeField = 'entity_type';

    // Model classes that will inherit from this class.
    protected static $singleTableSubclasses = [
        Course::class,
    ];

    // Fields that will be retrieved and updated from all models.
    protected static $persisted = [
        'entity_type',
        'name',
        'type_id',
        'sub_type_id',
        'organizational_unit_id',
        'project_id',
        'state_id',
        'process_instance_id',
        'primary_contact',
        'manager',
        'design_id',
        'design_assessment_id',
        'operational_details_id',
        'solution_development_id',
        'offering_and_enrolment_estimates_id',
        'development_assessment_id',
        'communications_material_id',
        'communications_material_assessment_id',
        'created_by',
        'updated_by',
    ];

    protected $table = 'learning_products';

    protected $fillable = [
        'name',
        'type_id',
        'sub_type_id',
        'organizational_unit_id',
        'project_id',
        'state_id',
        'process_instance_id',
        'primary_contact',
        'manager',
        'design_id',
        'design_assessment_id',
        'operational_details_id',
        'solution_development_id',
        'offering_and_enrolment_estimates_id',
        'development_assessment_id',
        'communications_material_id',
        'communications_material_assessment_id',
        'created_by',
        'updated_by',
    ];

    protected $with = [
        'type',
        'subType',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $dispatchesEvents = [
        'created' => LearningProductCreated::class,
        'updated' => LearningProductUpdated::class,
        'deleted' => LearningProductDeleted::class,
    ];

    public function type()
    {
        return $this->belongsTo(LearningProductType::class);
    }

    public function subType()
    {
        return $this->belongsTo(LearningProductType::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function primaryContact()
    {
        return $this->belongsTo(User::class, 'primary_contact');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager');
    }

    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    public function designAssessment()
    {
        return $this->belongsTo(DesignAssessment::class);
    }

    public function operationalDetails()
    {
        return $this->belongsTo(OperationalDetails::class);
    }

    public function solutionDevelopment()
    {
        return $this->belongsTo(SolutionDevelopment::class);
    }

    public function offeringAndEnrolmentEstimates()
    {
        return $this->belongsTo(OfferingAndEnrolmentEstimates::class);
    }

    public function developmentAssessment()
    {
        return $this->belongsTo(DevelopmentAssessment::class);
    }

    public function communicationsMaterial()
    {
        return $this->belongsTo(CommunicationsMaterial::class);
    }

    public function communicationsMaterialAssessment()
    {
        return $this->belongsTo(CommunicationsMaterialAssessment::class);
    }

    /**
     * Check whether or not a learning product is of a specific type.
     *
     * @param  string|array $types Types may use dot notation (i.e. course.instructor-led)
     * @return boolean
     */
    public function isType($types)
    {
        if (is_string($types)) {
            $types = [$types];
        }
        $learningProductType = $this->type->name_key;
        $learningProductType .= $this->subType ? '.' . $this->subType->name_key : '';

        return in_array($learningProductType, $types);
    }
}
