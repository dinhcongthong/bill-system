<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_service', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('contract_id')->unsigned();
            $table->integer('service_id')->unsigned();
            $table->date('service_start_date');
            $table->integer('quantity_months');
            $table->integer('discount_rate')->nullable();
            $table->double('service_price')->nullable();
            $table->date('time_service');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_service');
    }
}
