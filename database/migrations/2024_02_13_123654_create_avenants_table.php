<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avenants', function (Blueprint $table) {
            $table->id();

            $table->string('descriptif')->nullable(true);
            $table->string('montantloyer')->nullable(true);
            $table->string('montantloyerbase')->nullable(true);
            $table->string('montantloyertom')->nullable(true);
            $table->string('montantcharge')->nullable(true);
            $table->string('tauxrevision')->nullable(true);
            $table->string('frequencerevision')->nullable(true);

            $table->date('dateenregistrement')->nullable(true);
            $table->date('daterenouvellement')->nullable(true);
            $table->date("dateecheance")->nullable(true);
            $table->date('datedebutcontrat')->nullable(true);
            $table->unsignedBigInteger('typecontrat_id')->nullable(true);
            $table->foreign('typecontrat_id')->references('id')->on('typecontrats')->onDelete('cascade');
            $table->unsignedBigInteger('typerenouvellement_id')->nullable(true);
            $table->foreign('typerenouvellement_id')->references('id')->on('typerenouvellements')->onDelete('cascade');
            $table->unsignedBigInteger('delaipreavi_id')->nullable(true);
            $table->foreign('delaipreavi_id')->references('id')->on('delaipreavis')->onDelete('cascade');
            $table->unsignedBigInteger('appartement_id')->nullable(true);
            $table->foreign('appartement_id')->references('id')->on('appartements')->onDelete('cascade');
            $table->unsignedBigInteger('locataire_id')->nullable(true);
            $table->foreign('locataire_id')->references('id')->on('locataires')->onDelete('cascade');

            $table->unsignedBigInteger('periodicite_id')->nullable(true);
            $table->foreign('periodicite_id')->references('id')->on('periodicites')->onDelete('cascade');

            $table->unsignedBigInteger('contrat_id')->nullable(true);
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
        Schema::dropIfExists('avenants');
    }
}
