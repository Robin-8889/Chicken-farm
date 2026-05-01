<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mortality_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chicken_batch_id')->constrained()->cascadeOnDelete();
            $table->date('record_date');
            $table->unsignedInteger('number_dead')->default(0);
            $table->string('cause_of_death')->nullable();
            $table->unsignedInteger('number_consumed_home')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['chicken_batch_id', 'record_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mortality_records');
    }
};