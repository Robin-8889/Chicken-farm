<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'chicken_batch_id',
        'category',
        'item_name',
        'quantity_purchased',
        'unit',
        'quantity_used',
        'remaining_quantity',
        'purchase_date',
        'expiry_date',
        'cost',
        'low_stock_threshold',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'expiry_date' => 'date',
            'quantity_purchased' => 'decimal:2',
            'quantity_used' => 'decimal:2',
            'remaining_quantity' => 'decimal:2',
            'cost' => 'decimal:2',
            'low_stock_threshold' => 'decimal:2',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ChickenBatch::class, 'chicken_batch_id');
    }
}