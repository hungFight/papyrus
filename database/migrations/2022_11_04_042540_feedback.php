<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Feedback extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->integer('idProduct')->length(250)->unsigned();

            $table->integer('idUser')->length(249)->unsigned();
            $table->string('content')->length(250);
            $table->timestamp('created_at')->nullable();
        });
        Schema::table('feedback', function ($table) {
            $table->foreign('idProduct')->references('id')->on('products');
            $table->foreign('idUser')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback');
    }
}