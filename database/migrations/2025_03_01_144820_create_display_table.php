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
            $table->unsignedBigInteger('product_sku');
            $table->integer('display_pcs_in')->default(0);
            $table->integer('display_pcs_out')->default(0);
            $table->integer('display_balance_pcs')->default(0);;
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
