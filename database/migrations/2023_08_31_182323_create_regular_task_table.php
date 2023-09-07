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
            $regularTask = $table->foreignIdFor(RegularTaskTemplate::class)->constrained();
            $table->string('picture_proof')->nullable();
            $table->string('status')->default('new');
            $table->smallInteger('coins');
            $table->foreignIdFor(Adult::class)->constrained()->nullable();
            $child = $table->foreignIdFor(Child::class)->constrained()->nullable();
            $table->timestamps();
            $table->unique([$regularTask->columns[0], $child->columns[0]]);
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
