<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('reserved_books', function (Blueprint $table) {
            $table->boolean('is_ready_for_pickup')->default(false);
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('reserved_books', function (Blueprint $table) {
            $table->dropColumn('is_ready_for_pickup');
        });
    }
};
