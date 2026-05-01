<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\ChickenBatch;

class MortalityRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'chicken_batch_id',
        'record_date',
        'number_dead',
        'cause_of_death',
        'number_consumed_home',
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