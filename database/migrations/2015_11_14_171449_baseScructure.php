<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BaseScructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('version_uuid');
            $table->unique('version_uuid');
            $table->string('name');
            $table->text('text');
            $table->text('chord');

            $table->integer('creator');
            $table->string('parent')->nullable();

            $table->timestamps();
            $table->foreign('parent')
                ->references('version_uuid')->on('songs')
                ->onDelete('cascade');
        });

        Schema::create('group_songs', function (Blueprint $table) {
            $table->string('group');

            $table->integer('song')->unsigned();
            $table->foreign('song')
                ->references('id')->on('songs')
                ->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('songs');
        Schema::dropIfExists('group_songs');
    }
}
