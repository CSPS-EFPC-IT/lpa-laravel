<?php

namespace App\Models\LearningProduct;

use App\Models\BaseModel;
use App\Models\Traits\UsesUserAudit;
use Illuminate\Database\Eloquent\SoftDeletes;

class LearningProductCode extends BaseModel
{
    use SoftDeletes, UsesUserAudit;

    protected $fillable = [
        'active',
        'code',
        'comments',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $hidden = [
        'pivot',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
