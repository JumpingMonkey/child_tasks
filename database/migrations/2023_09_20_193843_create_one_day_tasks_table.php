<?php

use App\Models\ProofType;
use App\Models\TaskIcon;
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
        Schema::create('one_day_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('description', 500);
            $table->foreignIdFor(TaskIcon::class);
            $table->string('status');
            $table->integer('expected_duration')->default(0);
            $table->foreignIdFor(ProofType::class)->constrained();
            $table->mediumInteger('coins')->default(1);
            $table->boolean('is_timer_done')->default(false);
            $table->boolean('is_unlock_required')->default(false);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->unsignedBigInteger('adult_id')->nullable();
            $table->foreign('adult_id')
                ->references('id')
                ->on('adults')
                ->nullOnDelete();
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
        Schema::dropIfExists('one_day_tasks');
    }
};
