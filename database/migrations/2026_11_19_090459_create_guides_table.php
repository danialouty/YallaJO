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
        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_organizer_id')->unique()->constrained('activity_organizers')->cascadeOnDelete();
            $table->text('bio');
            $table->integer('experience_years');
            $table->json('specialization');
            $table->string('cetification')->nullable();
            $table->enum('status', ['Available','Busy'])->default('Available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guides');
    }
};
