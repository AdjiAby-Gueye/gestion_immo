<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewssfieldsssInAppartementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appartements', function (Blueprint $table) {

            $table->unsignedBigInteger('locataire_id')->nullable(true);
            $table->foreign('locataire_id')->references('id')->on('locataires')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appartements', function (Blueprint $table) {
            //
            $table->dropForeign(['locataire_id']);
            $table->dropColumn('locataire_id');
        });
    }
}
