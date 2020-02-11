<?php

namespace App\Models\LearningProduct\Design;

use App\Models\BaseModel;
use App\Models\Lists\Region;

class ExpectedAnnualParticipants extends BaseModel
{
    protected $fillable = [
        'design_id',
        'region_id',
        'expected_annual_participant_number',
    ];

    protected $with = [
        'region',
    ];

    public $timestamps = false;

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
