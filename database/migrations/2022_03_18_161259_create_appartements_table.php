<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppartementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appartements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codeappartement')->nullable();
            $table->string('nom')->nullable();
            $table->integer('isassurance')->nullable();
            $table->integer('iscontrat')->nullable();
            $table->integer('islocataire')->nullable();
            $table->string('superficie')->nullable();
            $table->string('image')->nullable();
            $table->string('isdemanderesiliation')->nullable();
            $table->integer('commissionvaleur')->nullable();
            $table->integer('commissionpourcentage')->nullable();
            $table->integer('tva')->nullable();
            $table->integer('brs')->nullable();
            $table->integer('tlv')->nullable();
            $table->integer('montantloyer')->nullable();
            $table->integer('montantcaution')->nullable();
            $table->integer('position')->nullable();
            $table->integer('typevente')->nullable(true);
            $table->string('montantvilla')->nullable(true);
            $table->string('prixappartement')->nullable(true);
            $table->string('niveau')->nullable();


            $table->unsignedBigInteger('immeuble_id')->nullable(true);
            $table->foreign('immeuble_id')->references('id')->on('immeubles')->onDelete('cascade');
            $table->unsignedBigInteger('proprietaire_id')->nullable(true);
            $table->foreign('proprietaire_id')->references('id')->on('proprietaires')->onDelete('cascade');
            $table->unsignedBigInteger('typeappartement_id')->nullable(true);
            $table->foreign('typeappartement_id')->references('id')->on('typeappartements')->onDelete('cascade');
            $table->unsignedBigInteger('frequencepaiementappartement_id')->nullable(true);
            $table->foreign('frequencepaiementappartement_id')->references('id')->on('frequencepaiementappartements')->onDelete('cascade');
            $table->unsignedBigInteger('etatappartement_id')->nullable(true);
            $table->foreign('etatappartement_id')->references('id')->on('etatappartements')->onDelete('cascade');

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
        Schema::dropIfExists('appartements');
    }
}
