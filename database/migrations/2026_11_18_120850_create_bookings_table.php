<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adventure_id')->constrained('adventures')->cascadeOnDelete();
            $table->foreignId('adventurer_id')->constrained('adventurers')->cascadeOnDelete();
            $table->integer('count');
            $table->decimal('unit_price', 9, 2);
            $table->decimal('commission_rate', 9, 2);
            $table->decimal('total_amount', 9, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
