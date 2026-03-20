<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIGeneration extends Model
{
    protected $table = 'ai_generations';

    protected $fillable = [
        'type', 'model', 'prompt', 'result', 'tokens_used', 'cost',
        'source_type', 'source_id', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return ['tokens_used' => 'integer', 'cost' => 'decimal:4'];
    }

    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function scopeByType($q, string $t) { return $q->where('type', $t); }
}
