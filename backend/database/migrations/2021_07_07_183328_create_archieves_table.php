<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchievesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archieves', function (Blueprint $table) {
            $table->id();
            // user
            $table->unsignedBigInteger('uid');
            $table->foreign('uid')->references('id')->on('users');
            // introduce
            $table->string("title");
            $table->text("description");
            // file
            $table->string('mime_type');
            $table->string('archieve_name');
            $table->string('extension');
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
        Schema::dropIfExists('archieves');
    }
}
