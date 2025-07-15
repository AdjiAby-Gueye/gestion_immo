<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaiementecheancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paiementecheances', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('avisecheance_id')->nullable();
            $table->foreign('avisecheance_id')->references('id')->on('avisecheances')->onDelete('cascade');
            
            $table->date('date'); 

            $table->integer('montant'); 
            $table->integer('receipt_number')->nullable(true);
            \App\Outil::listenerUsers($table);
            $table->string('montantenattente')->nullable(true);
            $table->integer('etat')->nullable(true);
            $table->string('montantencaisse')->nullable(true);

            $table->string("numero_cheque")->nullable();
            $table->string("justificatif")->nullable(true);

            $table->string("numero")->nullable();
            
            $table->string('periodes')->nullable();

            $table->unsignedBigInteger('modepaiement_id');
            $table->foreign('modepaiement_id')->references('id')->on('modepaiements')->onDelete('cascade');

            $table->text('commentaire')->nullable(); 

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
        Schema::dropIfExists('paiementecheances');
    }
}
