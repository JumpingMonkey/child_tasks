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
            $table->tinyText('adult_type')->nullable();
        });

        Schema::table('adult_child', function (Blueprint $table) {
            $table->tinyText('adult_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adults', function (Blueprint $table) {
            $table->dropColumn('adult_type');
        });

        Schema::table('adult_child', function (Blueprint $table) {
            $table->tinyText('adult_type')->nullable(false)->change();
        });
    }
};
