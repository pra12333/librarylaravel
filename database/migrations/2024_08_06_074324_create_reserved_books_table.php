<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservedBooksTable extends Migration
{
    public function up()
    {
        Schema::create('reserved_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->timestamp('reserved_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reserved_books');
    }
}
