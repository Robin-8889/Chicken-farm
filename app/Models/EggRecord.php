<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\ChickenBatch;

class EggRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'chicken_batch_id',
        'record_date',
        'total_eggs_produced',
        'broken_eggs',
        'eggs_consumed_home',
        'eggs_sold',
        'remaining_eggs',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'record_date' => 'date',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ChickenBatch::class, 'chicken_batch_id');
    }
}