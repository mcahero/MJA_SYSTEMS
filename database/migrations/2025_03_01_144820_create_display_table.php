<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisplayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('display', function (Blueprint $table) {
            $table->id();
            $table->integer('buffer_pcs');
            $table->unsignedBigInteger('product_sku');
            $table->integer('pcs');
            $table->string('checker')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();

             $table->foreign('product_sku')->references('id')->on('productlist')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('display');
    }
}
