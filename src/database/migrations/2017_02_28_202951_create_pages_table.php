<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->integer('lft')->nullable();
            $table->integer('rgt')->nullable();
            $table->integer('depth')->nullable();

            $table->string('cover')->nullable();
            $table->string('action')->nullable();
            $table->string('icon')->nullable();
            $table->string('template')->nullable();
            $table->string('middlewares')->nullable();
            $table->string('route_name')->nullable();
            $table->json('prefix')->nullable();
            $table->json('url');
            $table->json('title');
            $table->json('body')->nullable();
            $table->json('desc')->nullable();
            $table->json('meta')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
