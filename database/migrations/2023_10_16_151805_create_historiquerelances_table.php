<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriquerelancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historiquerelances', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('contrat_id')->nullable(true);
            $table->foreign('contrat_id')->references('id')->on('contrats')->onDelete('cascade');

            $table->unsignedBigInteger('locataire_id')->nullable(true);
            $table->foreign('locataire_id')->references('id')->on('locataires')->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable(true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unsignedBigInteger('inbox_id')->nullable(true);
            $table->foreign('inbox_id')->references('id')->on('inboxs')->onDelete('cascade');
            $table->date("date_envoie")->nullable(true);
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
        Schema::dropIfExists('historiquerelances');
    }
}
