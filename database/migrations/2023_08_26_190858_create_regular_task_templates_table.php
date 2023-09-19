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
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->integer('expected_duration')->nullable();
            $table->boolean('is_general_available')->default(false);
            $table->mediumInteger('coins')->default(1);
            $table->foreignIdFor(ProofType::class)->constrained();
            $table->foreignIdFor(Schedule::class)->constrained();
            $table->boolean('status')->default(false);
            $table->foreignIdFor(Adult::class)->nullable()->constrained();
            $table->unsignedBigInteger('child_id');
            $table->foreign('child_id')
                ->references('id')
                ->on('children')
                ->cascadeOnDelete();
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
