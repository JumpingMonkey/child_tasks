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
        Schema::table('rewards', function (Blueprint $table) {
            $table->dateTime('claimed_by_date')->nullable();
            $table->unsignedBigInteger('claimed_by')->nullable();
            $table->foreign('claimed_by')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->dropForeign(['claimed_by']);
            $table->dropColumn('claimed_by');
            $table->dropColumn('claimed_by_date');
        });
    }
};
