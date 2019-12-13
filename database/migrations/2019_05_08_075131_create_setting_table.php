<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name_poste');
            $table->text('address_poste');
            $table->string('area_name');
            $table->string('path_logo_file');
            $table->string('tax_code');
            $table->string('phone_poste');
            $table->string('email_poste');
            $table->string('vat_rate');
            $table->double('exchange_rate');
            $table->string('bank_name');
            $table->string('acc_bank_name');
            $table->string('acc_bank_number');
            $table->integer('parent_id')->default(0);
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
        Schema::dropIfExists('setting');
    }
}
