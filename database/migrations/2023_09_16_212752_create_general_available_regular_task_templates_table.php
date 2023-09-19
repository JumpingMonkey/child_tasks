<?php

use App\Models\ProofType;
use App\Models\Schedule;
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
        Schema::create('general_available_regular_task_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('description', 500);
            $table->string('icon')->nullable();
            $table->integer('expected_duration')->nullable();
            $table->mediumInteger('coins')->default(1);
            $table->foreignIdFor(ProofType::class)->constrained();
            $table->foreignIdFor(Schedule::class)->constrained();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_available_regular_task_templates');
    }
};