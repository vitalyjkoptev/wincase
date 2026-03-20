<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class N8NWorkflow extends Model
{
    protected $table = 'n8n_workflows';

    protected $fillable = [
        'code', 'name', 'n8n_workflow_id', 'module', 'trigger_type',
        'frequency', 'description', 'is_active', 'last_status',
        'last_executed_at', 'execution_count', 'config',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_executed_at' => 'datetime',
            'execution_count' => 'integer',
            'config' => 'array',
        ];
    }

    public function scopeActive($q) { return $q->where('is_active', true); }
    public function scopeModule($q, string $module) { return $q->where('module', $module); }
}
