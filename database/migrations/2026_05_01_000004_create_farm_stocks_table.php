<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farm_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('item_name');
            $table->decimal('quantity_purchased', 10, 2)->default(0);
            $table->string('unit')->default('bags');
            $table->decimal('quantity_used', 10, 2)->default(0);
            $table->decimal('remaining_quantity', 10, 2)->default(0);
            $table->date('purchase_date');
            $table->date('expiry_date')->nullable();
            $table->decimal('cost', 12, 2)->default(0);
            $table->decimal('low_stock_threshold', 10, 2)->default(10);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farm_stocks');
    }
};