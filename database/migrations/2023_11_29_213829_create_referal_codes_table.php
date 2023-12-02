<?php

use App\Models\Adult;
use App\Models\ReferalCode;
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
        Schema::create('referal_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignIdFor(Adult::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referal_codes');
    }
};
