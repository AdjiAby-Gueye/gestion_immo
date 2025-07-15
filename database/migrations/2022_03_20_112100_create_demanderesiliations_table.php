<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemanderesiliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demanderesiliations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('datedebutcontrat');
            $table->date('datedemande');
            $table->string('delaipreavisrespecte');
            $table->date('dateeffectivite');
            $table->string('raisonnonrespectdelai')->nullable(true);
            $table->string('delaipreavis')->nullable();
            $table->string('etat')->nullable(true);
            $table->string('status')->nullable() ;
            
            $table->string('motif')->nullable() ;
            $table->string('document')->nullable() ;

            $table->unsignedBigInteger('contrat_id')->nullable(true);
            $table->foreign('contrat_id')->references('id')->on('contrats')->onDelete('cascade');
            $table->string('retourcaution')->nullable(true);

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
        Schema::dropIfExists('demanderesiliations');
    }
}
