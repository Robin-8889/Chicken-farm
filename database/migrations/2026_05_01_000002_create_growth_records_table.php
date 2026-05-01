<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('growth_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chicken_batch_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('week_number');
            $table->decimal('average_weight_kg', 8, 2);
            $table->decimal('feed_consumed_kg', 8, 2)->default(0);
            $table->string('health_status');
            $table->unsignedInteger('mortality_recorded')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['chicken_batch_id', 'week_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('growth_records');
    }
};