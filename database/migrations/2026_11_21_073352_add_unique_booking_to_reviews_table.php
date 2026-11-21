<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unique('booking_id');
        });
    }

    public function down(): void
    {
        if (in_array(Schema::getConnection()->getDriverName(), ['mysql', 'mariadb'], true)
            && ! collect(Schema::getIndexes('reviews'))->contains(
                fn (array $index) => $index['name'] !== 'reviews_booking_id_unique'
                    && ($index['columns'][0] ?? null) === 'booking_id'
            )) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->index('booking_id');
            });
        }

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique(['booking_id']);
        });
    }
};
