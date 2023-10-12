<?php

use App\Models\Task;
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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login')->nullable();
            $table->integer('coins')->default(0);
            $table->boolean('is_parent')->default(1);
            $table->foreignIdFor(User::class)->nullable()->constrained();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_login');
            $table->dropColumn('coins');
            $table->dropColumn('is_parent');
            $table->dropConstrainedForeignIdFor(User::class);
        });
    }
};
