<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('interest_id');
            $table->integer('post_type');
            $table->text('content');
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('post_expressions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('post_id');
            $table->integer('expression_id');
            $table->timestamps();
        });

        Schema::create('post_stickers', function (Blueprint $table) {
            $table->integer('post_id');
            $table->integer('sticker_id');
        });

        Schema::create('post_views', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->integer('user_id');
        });

        Schema::create('post_hashtags', function (Blueprint $table) {
            $table->integer('post_id');
            $table->string('name');
        });

        Schema::create('post_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('post_id');
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('post_links', function (Blueprint $table) {
            $table->integer('post_id');
            $table->string('url');
            $table->string('title');
            $table->text('description');
            $table->string('link');
            $table->string('image');
            $table->string('embed_url');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posts');
        Schema::drop('post_expressions');
        Schema::drop('post_stickers');
        Schema::drop('post_views');
        Schema::drop('post_hashtags');
        Schema::drop('post_reports');
        Schema::drop('post_links');
    }
}
