<?php

use App\Models\Adult;
use App\Models\Child;
use App\Models\ProofType;
use App\Models\Schedule;
use App\Models\TaskTemplate;
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
        Schema::create('regular_task_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('description', 500);
            $table->string('icon');
            $table->boolean('is_general_available')->default(0);
            $table->integer('expected_duration')->nullable();
            $table->tinyText('status');
            $table->mediumInteger('coins');
            $table->foreignIdFor(Adult::class)->nullable();
            $table->foreignIdFor(Child::class)->nullable();
            $table->foreignIdFor(ProofType::class);
            $table->foreignIdFor(Schedule::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regular_task_templates');
    }
};
