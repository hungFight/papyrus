<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InformationCustemerBuy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idProduct')->length(250)->unsigned();
            $table->string('name')->length(40);
            $table->integer('phone')->length(11);
            $table->string('address')->length(250);
            $table->string('note')->nullable();
            $table->float('price');
            $table->int('quantity')->length(10);
            $table->timestamp('created_at')->nullable();
        });
        Schema::table('order', function ($table) {
            $table->foreign('idProduct')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order');
    }
}