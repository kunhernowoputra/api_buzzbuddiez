<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePhotos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->string('thumbnail');
        });

        Schema::create('photo_expressions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('photo_id');
            $table->integer('expression_id');
        });

        Schema::create('photo_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('photo_id');
            $table->integer('sticker_id');
            $table->text('comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('photos');
        Schema::drop('photo_expressions');
        Schema::drop('photo_comments');
    }
}
