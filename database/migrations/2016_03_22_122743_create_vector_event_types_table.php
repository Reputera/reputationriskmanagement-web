<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVectorEventTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vector_event_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vector_id');
            $table->string('event_type');
            $table->timestamps();

            $table->foreign('vector_id')->references('id')->on('vectors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vector_event_types');
    }
}
