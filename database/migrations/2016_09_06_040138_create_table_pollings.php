<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePollings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pollings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->text('question');
            $table->date('end');
        });

        Schema::create('polling_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('polling_id');
            $table->string('answer');
        });

        Schema::create('polling_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('polling_item_id');
            $table->integer('post_id');
            $table->integer('user_id');
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
        Schema::drop('pollings');
        Schema::drop('polling_items');
        Schema::drop('polling_answers');
    }
}
