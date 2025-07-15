<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaiementloyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paiementloyers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('datepaiement');
            $table->string('codefacture');


            $table->string('montantfacture')->nullable(true);
            $table->string('debutperiodevalide')->nullable(true);
            $table->string('finperiodevalide')->nullable(true);

            $table->string('periode')->nullable();

            $table->unsignedBigInteger('contrat_id')->nullable(true);
            $table->foreign('contrat_id')->references('id')->on('contrats')->onDelete('cascade');
            $table->text("motif_annulation_paiement")->nullable(true);
            $table->date("date_annulation_paiement")->nullable(true);
            $table->date("date_reactivation_paiement")->nullable(true);
            $table->string("justificatif_paiement")->nullable(true);
            $table->integer('receipt_number')->nullable(true);

            \App\Outil::statusOfObject($table);
            $table->rememberToken();
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
        Schema::dropIfExists('paiementloyers');
    }
}
