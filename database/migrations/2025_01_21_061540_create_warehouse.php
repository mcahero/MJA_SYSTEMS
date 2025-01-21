<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sku_id');
            $table->string('transaction_number');
            $table->integer('pcs');
            $table->string('checker');
            $table->string('expiry_date');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('warehouse');
    }
}
