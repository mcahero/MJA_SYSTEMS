<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivinglistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receivinglist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sku_id');
            $table->string('transaction_number');
            $table->integer('pcs_in')->default(0);
            $table->integer('pcs_out')->default(0);
            $table->integer('balance_pcs')->default(0);;
            $table->string('checker')->nullable();
            $table->string('expiry_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->foreign('sku_id')->references('id')->on('productlist')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receivinglist');
    }

}
