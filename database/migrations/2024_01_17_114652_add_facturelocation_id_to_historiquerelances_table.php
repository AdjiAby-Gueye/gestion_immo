<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFacturelocationIdToHistoriquerelancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historiquerelances', function (Blueprint $table) {
            
            $table->unsignedBigInteger('facturelocation_id')->nullable(true);
            $table->foreign('facturelocation_id')->references('id')->on('facturelocations')->onDelete('cascade');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historiquerelances', function (Blueprint $table) {
            $table->dropForeign(['facturelocation_id']);
            $table->dropColumn('facturelocation_id');
            //
        });
    }
}
