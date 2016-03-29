<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instances', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('vector_id')->nullable();
            $table->string('entity_id');
            $table->string('type');
            $table->dateTime('start');
            $table->string('language');
            $table->string('source');
            $table->string('title');
            $table->text('fragment');
            $table->string('fragment_hash', 50)->index();
            $table->string('link', 500);
            $table->string('link_hash', 50)->index();
            $table->integer('risk_score');
            $table->unsignedInteger('positive_risk_score');
            $table->unsignedInteger('negative_risk_score');
            $table->double('positive_sentiment', 18, 17)->unsigned();
            $table->double('negative_sentiment', 18, 17)->unsigned();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('vector_id')->references('id')->on('vectors');

            $table->unique(['fragment_hash'], 'unique_fragment_hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('instances');
    }
}
