<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedReport extends Model
{
    protected $fillable = [
        'report_type', 'format', 'filename', 'path', 'parameters',
        'generated_by', 'file_size',
    ];

    protected $casts = [
        'parameters' => 'array',
    ];
}
