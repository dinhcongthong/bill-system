<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('setting_id')->unsigned();
            $table->string('company_name')->nullable();
            $table->string('status')->default('active');
            $table->string('client_in_charge');
            $table->string('phone_client_in_charge');
            $table->string('email_client_in_charge')->nullable();
            $table->text('address_client_in_charge');
            $table->string('tax_code')->nullable();
            $table->string('client_type_id')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
