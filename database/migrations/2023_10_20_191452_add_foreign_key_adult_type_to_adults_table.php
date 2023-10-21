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
        Schema::table('adults', function (Blueprint $table) {
            $table->unsignedBigInteger('adult_type_id')->nullable();
            $table->foreign('adult_type_id')
                ->references('id')
                ->on('adult_types')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adults', function (Blueprint $table) {
            $table->dropForeign(['adult_type_id']);
            $table->dropColumn('adult_type_id');
        });
    }
};
