<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\EggRecord;
use App\Models\Expense;
use App\Models\FarmStock;
use App\Models\GrowthRecord;
use App\Models\MortalityRecord;
use App\Models\Sale;

class ChickenBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'date_of_arrival',
        'chicken_type',
        'breed_name',
        'number_entered',
        'initial_average_weight_kg',
        'supplier_source',
        'purchase_cost',
        'expected_purpose',
        'status',
        'next_vaccination_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_of_arrival' => 'date',
            'next_vaccination_at' => 'date',
            'initial_average_weight_kg' => 'decimal:2',
            'purchase_cost' => 'decimal:2',
        ];
    }

    public function growthRecords(): HasMany
    {
        return $this->hasMany(GrowthRecord::class);
    }

    public function eggRecords(): HasMany
    {
        return $this->hasMany(EggRecord::class);
    }

    public function mortalityRecords(): HasMany
    {
        return $this->hasMany(MortalityRecord::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function farmStocks(): HasMany
    {
        return $this->hasMany(FarmStock::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}