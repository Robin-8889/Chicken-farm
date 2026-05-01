<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\ChickenBatch;

class GrowthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'chicken_batch_id',
        'week_number',
        'average_weight_kg',
        'feed_consumed_kg',
        'health_status',
        'mortality_recorded',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'average_weight_kg' => 'decimal:2',
            'feed_consumed_kg' => 'decimal:2',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ChickenBatch::class, 'chicken_batch_id');
    }
}