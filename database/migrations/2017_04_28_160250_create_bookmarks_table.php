<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookmarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmarks', function($table) {
            $table->increments('id');
            $table->string('pinboard_id')->unique();
            $table->bigInteger('time_posted');
            $table->string('title');
            $table->mediumText('description');
            $table->mediumText('url');
            $table->boolean('starred');
            $table->unsignedInteger('others');
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
        Schema::drop('bookmarks');
    }
}
