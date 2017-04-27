<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableChat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->integer('chat_room_id');
            $table->integer('user_id');
            $table->integer('to_id');
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('to_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('chats');
        Schema::drop('chat_rooms');
    }
}
