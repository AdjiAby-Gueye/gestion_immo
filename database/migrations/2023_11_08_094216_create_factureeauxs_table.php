<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactureeauxsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factureeauxs', function (Blueprint $table) {
            $table->id();
            $table->string('debutperiode')->nullable();
            $table->string('quantitedebut')->nullable();
            $table->string('finperiode')->nullable();
            $table->string('quantitefin')->nullable();
            $table->string('consommation')->nullable();
            $table->string('prixmetrecube')->nullable();
            $table->string('montantfacture')->nullable();
            $table->string('soldeanterieur')->nullable();
            $table->unsignedBigInteger('contrat_id')->nullable();
            $table->foreign('contrat_id')->references('id')->on('contrats')->onDelete('cascade');
                        $table->date('dateecheance')->nullable();


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
        Schema::dropIfExists('factureeauxs');
    }
}
