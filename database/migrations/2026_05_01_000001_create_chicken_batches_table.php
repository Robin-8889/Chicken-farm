<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chicken_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->unique();
            $table->date('date_of_arrival');
            $table->string('chicken_type');
            $table->string('breed_name');
            $table->unsignedInteger('number_entered');
            $table->decimal('initial_average_weight_kg', 8, 2)->default(0);
            $table->string('supplier_source')->nullable();
            $table->decimal('purchase_cost', 12, 2)->default(0);
            $table->string('expected_purpose');
            $table->string('status')->default('active');
            $table->date('next_vaccination_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chicken_batches');
    }
};