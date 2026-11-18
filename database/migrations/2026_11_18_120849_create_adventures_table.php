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
        Schema::create('adventures', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->foreignId('activity_organizer_id')->constrained('activity_organizers')->cascadeOnDelete();
            $table->enum('difficulty_level', ['Easy','Medium','Hard'])->default('Easy');
            $table->integer('capacity');
            $table->dateTime('start_at');
            $table->dateTime('ends_at');
            $table->decimal('price', 9, 2);
            $table->enum('status', ['Active','Suspended'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adventures');
    }
};
