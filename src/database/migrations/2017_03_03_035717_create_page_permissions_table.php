<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('page_has_roles', function (Blueprint $table) {
            $table->integer('page_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('page_id')
                ->references('id')
                ->on('pages')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['role_id', 'page_id']);
        });

        Schema::create('page_has_permissions', function (Blueprint $table) {
            $table->integer('page_id')->unsigned();
            $table->integer('permission_id')->unsigned();

            $table->foreign('page_id')
                ->references('id')
                ->on('pages')
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->primary(['page_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('page_has_roles');
        Schema::drop('page_has_permissions');
    }
}
