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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('mail', 256)->unique();
            $table->string('password', 255);
            $table->string('salt', 30);
            $table->integer('stretch');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('first_name_kana', 50);
            $table->string('last_name_kana', 50);
            $table->date('birthday');
            $table->string('post_code', 7);
            $table->string('first_address', 255);
            $table->string('last_address', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
