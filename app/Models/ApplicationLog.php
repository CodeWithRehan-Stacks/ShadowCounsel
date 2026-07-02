<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationLog extends Model
{
    protected $fillable = [
        'level',
        'level_name',
        'message',
        'context',
        'extra',
    ];

    protected $casts = [
        'context' => 'array',
        'extra' => 'array',
    ];
}
