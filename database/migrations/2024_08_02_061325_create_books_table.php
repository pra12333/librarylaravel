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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('bookname');
            $table->unsignedBigInteger('genreid');
            $table->string('bookpicture')->nullable();
            $table->string('author');
            $table->date('release_date');
            $table->string('isbn')->unique();
            $table->integer('total_no_of_copies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
