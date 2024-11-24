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
        Schema::table('reserved_books', function (Blueprint $table) {
            $table->boolean('is_picked_up')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reserved_books', function (Blueprint $table) {
            $table->dropColumn('is_picked_up');
        });
    }
};
