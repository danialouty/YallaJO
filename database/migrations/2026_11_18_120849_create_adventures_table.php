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
            $table->enum('category', ['Hiking','Diving','Camping','Cyciling','Zaitoon day out','kayaking','climbing','Sky Diving','Blimping','Toursim'])->default('Hiking');
            $table->text('location');
            $table->decimal('duration_hours',5,2);
            $table->decimal('price', 9, 2);
            $table->text('requirements');
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
