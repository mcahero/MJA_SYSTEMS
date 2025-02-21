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
            $table->unsignedBigInteger('sku_id');
            $table->integer('pcs');
            $table->string('checker');
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
        Schema::dropIfExists('buffer');
    }
}
