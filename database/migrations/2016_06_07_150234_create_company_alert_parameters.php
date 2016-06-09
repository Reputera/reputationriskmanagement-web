<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyAlertParameters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_alert_parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->integer('min_threshold');
            $table->integer('max_threshold');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('company_alert_parameters');
    }
}
