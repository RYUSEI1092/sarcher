<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('isbn', 13);
            $table->text('book_img');
            $table->string('book_title', 255);
            $table->string('book_genre', 255);
            $table->string('book_author', 255);
            $table->string('publisher_name', 255);
            $table->string('library', 255);
            $table->date('rental_on');
            $table->date('return_on');
            $table->date('returned_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rentals');
    }
};
