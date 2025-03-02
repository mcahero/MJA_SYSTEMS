<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bo', function (Blueprint $table) {
           $table->id();
            $table->unsignedBigInteger('product_sku');
            $table->integer('bo_pcs_in')->default(0);
            $table->integer('bo_pcs_out')->default(0);
            $table->integer('bo_balance_pcs')->default(0);;
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
        Schema::dropIfExists('bo');
    }
}
