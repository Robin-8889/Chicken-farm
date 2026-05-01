<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('egg_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chicken_batch_id')->constrained()->cascadeOnDelete();
            $table->date('record_date');
            $table->unsignedInteger('total_eggs_produced')->default(0);
            $table->unsignedInteger('broken_eggs')->default(0);
            $table->unsignedInteger('eggs_consumed_home')->default(0);
            $table->unsignedInteger('eggs_sold')->default(0);
            $table->unsignedInteger('remaining_eggs')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['chicken_batch_id', 'record_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('egg_records');
    }
};