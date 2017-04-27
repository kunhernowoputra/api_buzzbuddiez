<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('username');
            $table->string('password');
            $table->string('api_token');
            $table->tinyInteger('register_type');
            $table->string('remember_token');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('fullname');
            $table->date('birthday')->nullable();
            $table->text('about');
            $table->string('phone');
            $table->string('gender');
            $table->string('location');
            $table->string('image');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('user_tags', function (Blueprint $table) {
            $table->integer('post_id');
            $table->integer('user_id');
        });

        Schema::create('user_interests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('interest_id');
            $table->timestamps();
        });

        Schema::create('user_buzzes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('to_id');
            $table->timestamps();
        });

        Schema::create('user_buddies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('counter');
        });

        Schema::create('user_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('device_token');
            $table->string('device_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
        Schema::drop('user_profiles');
        Schema::drop('user_tags');
        Schema::drop('user_interests');
        Schema::drop('user_buzzes');
        Schema::drop('user_buddies');
        Schema::drop('user_devices');
    }
}
