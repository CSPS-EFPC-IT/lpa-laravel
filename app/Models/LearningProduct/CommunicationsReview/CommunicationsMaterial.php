<?php

namespace App\Models\LearningProduct\CommunicationsReview;

use App\Models\Process\ProcessInstanceFormDataModel;

class CommunicationsMaterial extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'comments',
        'description_en',
        'description_fr',
        'disclaimer_en',
        'disclaimer_fr',
        'keywords_en',
        'keywords_fr',
        'linguistic_services_consulted',
        'material_original_language',
        'note_en',
        'note_fr',
        'process_instance_form_id',
        'process_instance_id',
        'summary_en',
        'summary_fr',
        'title_en',
        'title_fr',
    ];

    protected $with = [
        //
    ];

    protected $casts = [
        'linguistic_services_consulted' => 'boolean'
    ];
}
