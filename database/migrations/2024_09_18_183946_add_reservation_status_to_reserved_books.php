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
            // Add the reservation_status column with default 'pending'
            $table->enum('reservation_status', ['pending', 'ready_for_pickup', 'expired'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reserved_books', function (Blueprint $table) {
            // Drop the reservation_status column when rolling back
            $table->dropColumn('reservation_status');
        });
    }
};
