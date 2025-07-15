<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVillaFieldsInAppartementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appartements', function (Blueprint $table) {

            //
            $table->string("lot")->nullable();
            $table->string("prixvilla")->nullable();
            $table->string("acomptevilla")->nullable();
            $table->integer("maturite")->nullable();

            $table->unsignedBigInteger('ilot_id')->nullable();
            $table->foreign('ilot_id')->references('id')->on('ilots')->onDelete('cascade');

            $table->unsignedBigInteger('periodicite_id')->nullable();
            $table->foreign('periodicite_id')->references('id')->on('periodicites')->onDelete('cascade');

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
        Schema::table('appartements', function (Blueprint $table) {
            //
            $table->dropForeign(['ilot_id']);
            $table->dropColumn('ilot_id');

            $table->dropForeign(['periodicite_id']);
            $table->dropColumn('periodicite_id');

            $table->dropForeign(['entite_id']);
            $table->dropColumn('entite_id');

            $table->dropColumn('lot');
            $table->dropColumn('prixvilla');
            $table->dropColumn('acomptevilla');
            $table->dropColumn('maturite');
        });
    }
}
