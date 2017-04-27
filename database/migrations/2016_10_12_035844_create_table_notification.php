<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('notification_type_id');
            $table->boolean('readed');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('notification_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notification_posts');
        Schema::drop('notification_types');
    }
}
