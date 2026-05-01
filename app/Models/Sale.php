<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'chicken_batch_id',
        'sale_type',
        'number_sold',
        'weight_sold_kg',
        'price_per_unit',
        'total_revenue',
        'buyer_name',
        'buyer_contact',
        'sale_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'sale_date' => 'date',
            'weight_sold_kg' => 'decimal:2',
            'price_per_unit' => 'decimal:2',
            'total_revenue' => 'decimal:2',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ChickenBatch::class, 'chicken_batch_id');
    }
}