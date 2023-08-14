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
        Schema::create('adult_child', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adult_id');
            $table->unsignedBigInteger('child_id');
            $table->tinyText('adult_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adult_child');
    }
};
