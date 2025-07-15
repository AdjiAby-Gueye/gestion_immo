<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvisecheancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avisecheances', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('periodicite_id')->nullable(true);
            $table->foreign('periodicite_id')->references('id')->on('periodicites')->onDelete('cascade');

            $table->unsignedBigInteger('contrat_id')->nullable(true);
            $table->foreign('contrat_id')->references('id')->on('contrats')->onDelete('cascade');

            $table->string('objet')->nullable();

            $table->string('amortissement')->nullable();

            $table->string('fraisgestion')->nullable();
            $table->string("signature")->nullable();
            $table->integer("est_signer")->default(0);
            $table->string('periodes')->nullable();
            $table->string("fraisdelocation")->nullable(true);
            $table->text("motif_annulation_paiement")->nullable(true);
            $table->date("date_annulation_paiement")->nullable(true);
            $table->date("date_reactivation_paiement")->nullable(true);
            $table->string("code_avis")->nullable(true);
            $table->date('datesignature')->nullable();


            $table->date('date')->nullable();

            $table->date('date_echeance')->nullable();

            \App\Outil::statusOfObject($table);
            \App\Outil::listenerUsers($table);

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
        Schema::dropIfExists('avisecheances');
    }
}
