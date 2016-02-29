<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->date('date');
            $table->decimal('open');
            $table->decimal('high')->nullable();
            $table->decimal('low')->nullable();
            $table->decimal('close')->nullable();
            $table->decimal('volume')->nullable();
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')
                ->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stock_history');
    }
}
