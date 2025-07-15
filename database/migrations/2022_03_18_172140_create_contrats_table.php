<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrats', function (Blueprint $table) {
            $table->bigIncrements('id');

            /*  $table->unsignedBigInteger('contratrenouvelle_id')->nullable(true);
          $table->foreign('id')->references('id')->on('contrats')->onDelete('cascade');*/
            $table->string("depot_initial")->default(0);

            $table->string('document')->nullable(true);
            $table->string('scanpreavis')->nullable(true);
            $table->string('descriptif')->nullable(true);
            $table->string('documentretourcaution')->nullable(true);
            $table->string('documentrecucaution')->nullable(true);
            $table->string('montantloyer')->nullable(true);
            $table->string('montantloyerbase')->nullable(true);
            $table->string('montantloyertom')->nullable(true);
            $table->string('montantcharge')->nullable(true);
            $table->string('tauxrevision')->nullable(true);
            $table->string('frequencerevision')->nullable(true);
            $table->integer("frais_gestion")->nullable(true);

            $table->string("mandataire")->nullable(true);
            $table->string("lieux_naissance")->nullable(true);
            $table->date("date_naissance")->nullable(true);
            $table->string("pays_naissance")->nullable(true);
            $table->string('numerodossier')->unique()->nullable(true);
            $table->bigInteger("fraisdegestion")->nullable(true);
            $table->bigInteger("codepartamortissemnt")->nullable(true);
            $table->bigInteger("fraislocative")->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('nomcompletbeneficiaire')->nullable();
            $table->string('telephonebeneficiaire')->nullable();
            $table->string('emailbeneficiaire')->nullable();
            $table->integer("est_soumis")->default(0);
            $table->integer("est_copreuneur")->default(0);
            $table->date('dateenregistrement')->nullable(true);
            $table->date('daterenouvellement')->nullable(true);
            $table->date('datepremierpaiement')->nullable(true);
            $table->date('dateretourcaution')->nullable(true);
            $table->date('daterenouvellementcontrat');
            $table->date('datedebutcontrat');
            $table->string("titrefoncier")->nullable(true);

            $table->string('retourcaution')->nullable(true);
            $table->string('status')->nullable(true);
            $table->integer('rappelpaiement')->nullable(true);
            $table->string('codeappartement')->nullable();

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

            $table->string("signatureclient")->nullable(true);
            $table->string("signaturedirecteur")->nullable(true);
            $table->integer("etatvalidation")->default(0);
            $table->unsignedBigInteger('usersigned_id')->nullable(true);
            $table->foreign('usersigned_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('contrats');
    }
}
