<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntiteIdInLocatiresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locataires', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('entite_id')->nullable();
            $table->foreign('entite_id')->references('id')->on('entites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locataires', function (Blueprint $table) {
            //
            $table->dropForeign(['entite_id']);
            $table->dropColumn('entite_id');
        });
    }
}
