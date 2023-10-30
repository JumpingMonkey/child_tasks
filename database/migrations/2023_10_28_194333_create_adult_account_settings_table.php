<?php

use App\Models\Adult;
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
        Schema::create('adult_account_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_child_notification_enabled')->default(false);
            $table->boolean('is_adult_notification_enabled')->default(false);
            $table->string('language')->default('en');
            $table->foreignIdFor(Adult::class)
                ->references('id')
                ->on('adults')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adult_account_settings');
    }
};
