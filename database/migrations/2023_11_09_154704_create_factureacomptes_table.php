<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactureacomptesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factureacomptes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->date('date_echeance')->nullable();
            $table->integer('montant');
            $table->string('commentaire')->nullable();
            $table->unsignedBigInteger('contrat_id');
            $table->foreign('contrat_id')->references('id')->on('contrats')->onDelete('cascade');

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
        Schema::dropIfExists('factureacomptes');
    }
}
