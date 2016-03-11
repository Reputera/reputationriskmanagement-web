<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyCompetitorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_competitor', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('competitor_company_id');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('competitor_company_id')->references('id')->on('companies');

            $table->unique(['company_id', 'competitor_company_id'], 'unique_company_competitor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_competitor', function (Blueprint $table) {
            $table->dropForeign('company_competitor_competitor_company_id_foreign');
        });
        Schema::drop('company_competitor');
    }
}
