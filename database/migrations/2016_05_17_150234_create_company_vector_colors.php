<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyVectorColors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_vector_colors', function (Blueprint $table) {
            $table->primary('id');
            $table->string('color', 10)->nullable();
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('vector_id');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
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
        Schema::drop('company_vector_colors');
    }
}
