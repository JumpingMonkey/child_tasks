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
        Schema::table('regular_task_templates', function (Blueprint $table) {
            $table->string('description', 500)->nullable()->change();
        });

        Schema::table('general_available_regular_task_templates', function (Blueprint $table) {
            $table->string('description', 500)->nullable()->change();
        });

        Schema::table('one_day_tasks', function (Blueprint $table) {
            $table->string('description', 500)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regular_task_templates', function (Blueprint $table) {
            $table->string('description', 500)->nullable(false)->change();
        });

        Schema::table('general_available_regular_task_templates', function (Blueprint $table) {
            $table->string('description', 500)->nullable(false)->change();
        });

        Schema::table('one_day_tasks', function (Blueprint $table) {
            $table->string('description', 500)->nullable(false)->change();
        });
    }
};
