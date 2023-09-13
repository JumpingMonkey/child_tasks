<?php

use App\Models\Adult;
use App\Models\Child;
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
        Schema::create('child_rewards', function (Blueprint $table) {
            $table->id();
            $table->tinyText('title');
            $table->smallInteger('price');
            $table->tinyText('status');
            $table->dateTime('claimed_by_date')->nullable();
            $table->foreignIdFor(Child::class)->constrained();
            $table->foreignIdFor(Adult::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_rewards');
    }
};
