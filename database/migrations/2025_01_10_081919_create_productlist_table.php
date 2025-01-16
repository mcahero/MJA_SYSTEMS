<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductlistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productlist', function (Blueprint $table) {
            $table->id();
            $table->string('product_fullname');
            $table->string('product_shortname')->nullable();
            $table->string('jda_systemname')->nullable();
            $table->string('product_sku');
            $table->string('product_barcode');
            $table->string('product_type');
            $table->string('product_warehouse');
            $table->string('product_entryperson');
            $table->string('product_remarks')->nullable();
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
        Schema::dropIfExists('productlist');
    }
}
