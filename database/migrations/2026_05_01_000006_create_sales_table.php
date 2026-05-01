<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chicken_batch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sale_type');
            $table->unsignedInteger('number_sold')->default(0);
            $table->decimal('weight_sold_kg', 8, 2)->nullable();
            $table->decimal('price_per_unit', 12, 2);
            $table->decimal('total_revenue', 12, 2);
            $table->string('buyer_name')->nullable();
            $table->string('buyer_contact')->nullable();
            $table->date('sale_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};