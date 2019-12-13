<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('client_id')->unsigned();
            $table->string('contract_code')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('tax_code')->nullable();
            $table->string('poste_in_charge');
            $table->string('representative');
            $table->string('status')->default('doing');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('vat')->default(0);
            $table->string('vat_status')->nullable();
            $table->integer('payment_times');
            $table->integer('invoice')->default(0);
            $table->string('contract_note')->nullable();
            $table->integer('quantity_months');
            $table->string('store_branch_id')->nullable();
            $table->double('payment_money')->nullable();
            $table->double('exchange_rate')->nullable();
            $table->string('payment_type')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('contracts');
    }
}
