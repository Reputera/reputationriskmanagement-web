<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstanceCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instance_country', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('instance_id');
            $table->unsignedInteger('country_id');

            $table->foreign('instance_id')->references('id')->on('instances');
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('instance_country');
    }
}
