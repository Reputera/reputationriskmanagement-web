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
            $table->string('entity_id'); // Recorded Future Instance ID
            $table->string('event_type');
            $table->string('original_language');
            $table->string('source');
            $table->string('title');
            $table->string('fragment', 600);
            $table->string('fragment_hash');
            $table->string('link');
            $table->float('positive_sentiment');
            $table->float('negative_sentiment');
            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');

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
