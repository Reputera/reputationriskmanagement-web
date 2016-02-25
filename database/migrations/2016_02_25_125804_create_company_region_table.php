<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyRegionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_region', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('region_id');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies');

            $table->foreign('region_id')
                ->references('id')
                ->on('regions');

            $table->unique(['company_id', 'region_id'], 'unique_company_region');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('company_region');
    }
}
