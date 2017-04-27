<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLanguage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');
            $table->char('lang');
        });

        Schema::create('language_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lang_id');
            $table->string('type');
        });

        Schema::create('language_descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lang_id');
            $table->integer('language_type_id');
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
        Schema::drop('languages');
        Schema::drop('language_types');
        Schema::drop('language_descriptions');
    }
}
