<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFiledsLocationVenteToContratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrats', function (Blueprint $table) {
            //
            $table->string("loyerpercusuite")->nullable();
            $table->string("mensualitesuite")->nullable();
            $table->string("mensualite")->nullable();
            $table->string("apportinitial")->nullable();
            $table->string("apportiponctuel")->nullable();
            $table->string("acompteinitial")->nullable();
            $table->string("prixvilla")->nullable();
            $table->integer("indemnite")->nullable();
            $table->string("fraiscoutlocationvente")->nullable();
            $table->string("clausepenale")->nullable();
            $table->integer("dureelocationvente")->nullable();
            $table->date("dateremisecles")->nullable();
            $table->date("dateecheance")->nullable();

            $table->integer("maturite")->nullable();

            $table->unsignedBigInteger('periodicite_id')->nullable();
            $table->foreign('periodicite_id')->references('id')->on('periodicites')->onDelete('cascade');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrats', function (Blueprint $table) {
            //
            $table->dropColumn('loyerpercusuite');
            $table->dropColumn('mensualitesuite');
            $table->dropColumn('mensualite');
            $table->dropColumn('apportinitial');
            $table->dropColumn('apportiponctuel');
            $table->dropColumn('acompteinitial');
            $table->dropColumn('prixvilla');
            $table->dropColumn('indemnite');
            $table->dropColumn('fraiscoutlocationvente');
            $table->dropColumn('clausepenale');
            $table->dropColumn('dureelocationvente');
            $table->dropColumn('dateremisecles');
            $table->dropColumn('dateecheance');

            $table->dropForeign(['periodicite_id']);
            $table->dropColumn('periodicite_id');

            $table->dropColumn('maturite');

        });
    }
}
