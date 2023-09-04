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
            $table->foreignIdFor(RegularTaskTemplate::class)->constrained();
            $table->string('picture_proof')->nullable();
            $table->string('status')->default('new');
            $table->foreignIdFor(Adult::class)->constrained()->nullable();
            $table->foreignIdFor(Child::class)->constrained()->nullable();
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
