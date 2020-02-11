<?php

namespace App\Models\LearningProduct\Development;

use App\Models\Process\ProcessInstanceFormDataModel;

class OfferingAndEnrolmentEstimates extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'comments',
        'process_instance_form_id',
        'process_instance_id',
    ];

    protected $with = [
        'offeringRegions',
    ];

    protected $appends = [
        'national_annual_bilingual_offering_number',
        'national_annual_english_offering_number',
        'national_annual_french_offering_number',
        'national_annual_simultaneous_interpretation_offering_number',
        'national_annual_total_offering_number',
    ];

    protected function getOfferingNumberFor($attribute)
    {
        if ($this->relationLoaded('offeringRegions')) {
            return $this->offeringRegions->sum($attribute);
        }
        return 0;
    }

    public function offeringRegions()
    {
        return $this->hasMany(OfferingRegion::class);
    }

    public function getNationalAnnualBilingualOfferingNumberAttribute()
    {
        return $this->getOfferingNumberFor('regional_annual_bilingual_offering_number');
    }

    public function getNationalAnnualEnglishOfferingNumberAttribute()
    {
        return $this->getOfferingNumberFor('regional_annual_english_offering_number');
    }

    public function getNationalAnnualFrenchOfferingNumberAttribute()
    {
        return $this->getOfferingNumberFor('regional_annual_french_offering_number');
    }

    public function getNationalAnnualSimultaneousInterpretationOfferingNumberAttribute()
    {
        return $this->getOfferingNumberFor('regional_annual_simultaneous_interpretation_offering_number');
    }

    public function getNationalAnnualTotalOfferingNumberAttribute()
    {
        return $this->getOfferingNumberFor('regional_annual_total_offering_number');
    }

    public function saveFormData(array $data)
    {
        $this->syncRelatedModels($data, [
            'offeringRegions',
        ]);

        parent::saveFormData($data);
    }
}
