<?php

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
        Schema::table('stock_notifications', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('email')->nullable();

            $table->unique(['product_id', 'user_id']);
            $table->unique(['product_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_notifications', function (Blueprint $table) {
            $table->dropUnique(['product_id', 'email']);
            $table->dropUnique(['product_id', 'user_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'user_id', 'email']);
        });
    }
};
