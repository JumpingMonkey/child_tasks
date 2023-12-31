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
            $table->unsignedBigInteger('price');
            $table->string('picture_proof')->nullable();
            $table->boolean('is_claimed')->default(false);
            $table->boolean('is_received')->default(false);
            $table->unsignedBigInteger('child_id');
            $table->foreign('child_id')
                ->references('id')
                ->on('children')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('adult_id');
            $table->foreign('adult_id')
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
        Schema::dropIfExists('child_rewards');
    }
};
