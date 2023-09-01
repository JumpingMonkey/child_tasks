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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->boolean('monday')->default(0);
            $table->boolean('tuesday')->default(0);
            $table->boolean('wednesday')->default(0);
            $table->boolean('thursday')->default(0);
            $table->boolean('friday')->default(0);
            $table->boolean('saturday')->default(0);
            $table->boolean('sunday')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
