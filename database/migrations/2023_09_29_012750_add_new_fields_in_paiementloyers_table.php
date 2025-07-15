<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsInPaiementloyersTable extends Migration
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
            $table->string("numero_cheque")->nullable();

            $table->unsignedBigInteger('periode_id')->nullable(true);
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('cascade');

            $table->unsignedBigInteger('modepaiement_id')->nullable(true);
            $table->foreign('modepaiement_id')->references('id')->on('modepaiements')->onDelete('cascade');
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
            $table->dropForeign(['modepaiement_id']);
            $table->dropColumn('modepaiement_id');

            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
            $table->dropColumn('numero_cheque');
        });
    }
}
