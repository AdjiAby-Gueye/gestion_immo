<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturelocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturelocations', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('typefacture_id')->nullable(true);
            $table->foreign('typefacture_id')->references('id')->on('typefactures')->onDelete('cascade');

            $table->unsignedBigInteger('periodicite_id')->nullable(true);
            $table->foreign('periodicite_id')->references('id')->on('periodicites')->onDelete('cascade');

            $table->unsignedBigInteger('contrat_id')->nullable(true);
            $table->foreign('contrat_id')->references('id')->on('contrats')->onDelete('cascade');
            
            // mettre dans paiement
           

            $table->string('objetfacture')->nullable();
            $table->date('datefacture')->nullable();
 
             $table->integer('nbremoiscausion')->nullable();
            $table->date("date_echeance")->nullable(true);

            $table->string('montant')->nullable();

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
        Schema::dropIfExists('facturelocations');
    }
}
