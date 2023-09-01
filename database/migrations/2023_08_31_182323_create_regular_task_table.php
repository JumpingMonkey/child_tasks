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
            $table->foreignIdFor(RegularTaskTemplate::class);
            $table->tinyInteger('coins');
            $table->string('picture_proof');
            $table->string('status');
            $table->foreignIdFor(Adult::class)->nullable();
            $table->foreignIdFor(Child::class)->nullable();
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
