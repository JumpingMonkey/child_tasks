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
        Schema::create('adult_referal_code', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Adult::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(ReferalCode::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adult_referal_code');
    }
};
