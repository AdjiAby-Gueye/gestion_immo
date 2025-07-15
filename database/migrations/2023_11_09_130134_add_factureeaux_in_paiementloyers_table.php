<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFactureeauxInPaiementloyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paiementloyers', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('factureeaux_id')->nullable();
            $table->foreign('factureeaux_id')->references('id')->on('factureeauxs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paiementloyers', function (Blueprint $table) {
            //
            $table->dropForeign(['factureeaux_id']);
            $table->dropColumn('factureeaux_id');
        });
    }
}
