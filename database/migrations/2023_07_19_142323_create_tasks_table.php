<?php

use App\Models\User;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->tinyText('title');
            $table->text('description');
            $table->tinyText('status');
            $table->timestamp('planned_and_date');
            $table->boolean('is_image_required');
            $table->unsignedSmallInteger('coins');
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('executor_id');
            $table->foreign('executor_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
