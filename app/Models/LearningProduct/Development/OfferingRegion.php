<?php

namespace App\Models\LearningProduct\Development;

use App\Models\BaseModel;
use App\Models\Lists\Region;

class OfferingRegion extends BaseModel
{
    protected $fillable = [
        'offering_and_enrolment_estimates_id',
        'region_id',
        'regional_annual_bilingual_offering_number',
        'regional_annual_english_offering_number',
        'regional_annual_french_offering_number',
        'regional_annual_simultaneous_interpretation_offering_number',
    ];

    protected $with = [
        'region',
    ];

    protected $appends = [
        'regional_annual_total_offering_number',
    ];

    public $timestamps = false;

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function getRegionalAnnualTotalOfferingNumberAttribute()
    {
        return collect([
            $this->regional_annual_bilingual_offering_number,
            $this->regional_annual_english_offering_number,
            $this->regional_annual_french_offering_number,
            $this->regional_annual_simultaneous_interpretation_offering_number,
        ])->sum();
    }
}
