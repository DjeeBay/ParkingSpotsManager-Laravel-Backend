<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name');
            $table->bigInteger('parkingid')->unsigned();
            $table->bigInteger('occupiedby')->unsigned()->nullable();
            $table->foreign('parkingid')->references('id')->on('parkings')->onDelete('cascade');
            $table->foreign('occupiedby')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('occupiedbydefaultby')->nullable();
            $table->boolean('isoccupiedbydefault');
            $table->dateTime('occupiedat')->nullable();
            $table->dateTime('releasedat')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spots');
    }
}
