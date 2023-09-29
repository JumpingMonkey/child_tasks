<?php

use App\Models\Adult;
use App\Models\Child;
use App\Models\RegularTaskTemplate;
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
        Schema::create('regular_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('regular_task_template_id');
            $table->foreign('regular_task_template_id')
                ->references('id')
                ->on('regular_task_templates')
                ->cascadeOnDelete();
            $table->string('picture_proof')->nullable();
            $table->string('status');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regular_tasks');
    }
};
