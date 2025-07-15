<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvisecheanceIdToHistoriquerelancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historiquerelances', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('avisecheance_id')->nullable(true);
            $table->foreign('avisecheance_id')->references('id')->on('avisecheances')->onDelete('cascade');
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
            //
            $table->dropForeign(['avisecheance_id']);
            $table->dropColumn('avisecheance_id');
        });
    }
}
