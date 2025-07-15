<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocatairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locataires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('prenom')->nullable(true);
            $table->string('nom')->nullable(true);
            $table->string('telephoneportable1')->nullable(true);
            $table->string('telephoneportable2')->nullable(true);
            $table->string('telephonebureau')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('profession')->nullable(true);
            $table->string('age')->nullable(true);
            $table->string('cni')->nullable(true);
            $table->string('passeport')->nullable(true);
            $table->string('nomentreprise')->nullable(true);
            $table->string('adresseentreprise')->nullable(true);
            $table->string('ninea')->nullable(true);
            $table->string('documentninea')->nullable(true);
            $table->string('numerorg')->nullable(true);
            $table->string('documentnumerorg')->nullable(true);
            $table->string('documentstatut')->nullable(true);
            $table->string('personnehabiliteasigner')->nullable(true);
            $table->string('fonctionpersonnehabilite')->nullable(true);
            $table->string('nompersonneacontacter')->nullable(true);
            $table->string('prenompersonneacontacter')->nullable(true);
            $table->string('emailpersonneacontacter')->nullable(true);
            $table->string('telephone1personneacontacter')->nullable(true);
            $table->string('telephone2personneacontacter')->nullable(true);
            $table->string('etatlocataire');
            $table->string('documentcontrattravail')->nullable();
            $table->string('documentcnipassport')->nullable();
            // date_naissance
            $table->date('datenaissance')->nullable(true);

            $table->string('revenus')->nullable();
            $table->string('contrattravail')->nullable();
            $table->string('expatlocale')->nullable();
            $table->string('nomcompletpersonnepriseencharge')->nullable();
            $table->string('telephonepersonnepriseencharge')->nullable();
            $table->string('numeroclient')->unique()->nullable(true);

            $table->string('situationfamiliale')->nullable(true);
            $table->string('codepostal')->nullable(true);
            $table->string('ville')->nullable(true);
            $table->string('nationalite')->nullable(true);
            $table->string('njf')->nullable(true);
            $table->string("est_copreuneur")->default(0);

            $table->unsignedBigInteger('typelocataire_id')->nullable(true);
            $table->foreign('typelocataire_id')->references('id')->on('typelocataires')->onDelete('cascade');
            $table->unsignedBigInteger('observation_id')->nullable(true);
            $table->foreign('observation_id')->references('id')->on('observations')->onDelete('cascade');

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
        Schema::dropIfExists('locataires');
    }
}
