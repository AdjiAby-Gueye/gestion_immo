<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSituationdepotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('situationdepots', function (Blueprint $table) {
            $table->id();
            $table->date('date');
         
            $table->unsignedBigInteger('facturelocation_id');
            $table->foreign('facturelocation_id')->references('id')->on( 'facturelocations')->onDelete('cascade');
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
        Schema::dropIfExists('situationdepots');
    }
}
