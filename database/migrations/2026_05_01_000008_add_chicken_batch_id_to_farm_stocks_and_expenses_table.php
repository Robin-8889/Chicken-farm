<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farm_stocks', function (Blueprint $table): void {
            $table->foreignId('chicken_batch_id')->nullable()->after('id')->constrained('chicken_batches')->nullOnDelete();
        });

        Schema::table('expenses', function (Blueprint $table): void {
            $table->foreignId('chicken_batch_id')->nullable()->after('id')->constrained('chicken_batches')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('farm_stocks', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('chicken_batch_id');
        });

        Schema::table('expenses', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('chicken_batch_id');
        });
    }
};
