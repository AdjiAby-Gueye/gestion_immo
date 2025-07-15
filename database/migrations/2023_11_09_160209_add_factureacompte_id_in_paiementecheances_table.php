<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFactureacompteIdInPaiementecheancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paiementecheances', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('factureacompte_id')->nullable(true);
            $table->foreign('factureacompte_id')->references('id')->on('factureacomptes')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paiementecheances', function (Blueprint $table) {
            //
            $table->dropForeign(['factureacompte_id']);
            $table->dropColumn('factureacompte_id');
        });
    }
}
