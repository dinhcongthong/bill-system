<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_branch', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('store_type_id')->nullable();
            $table->integer('client_id')->unsigned();
            $table->string('tax_code')->nullable();
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
        Schema::dropIfExists('store_branch');
    }
}
