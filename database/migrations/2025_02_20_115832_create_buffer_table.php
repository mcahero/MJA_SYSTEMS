<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBufferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buffer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receivinglist');
            $table->string('product_sku');
            $table->integer('pcs');
            $table->string('checker');
            $table->timestamps();


             $table->foreign('receivinglist')->references('id')->on('warehouse')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buffer');
    }
}
