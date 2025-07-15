<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfobancairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infobancaires', function (Blueprint $table) {
            $table->id();
            $table->string("banque")->nullable();
            $table->string("agence")->nullable();
            $table->string("codebanque")->nullable();
            $table->string("codeguichet")->nullable();
            $table->string("clerib")->nullable();
            $table->date("datedebut")->nullable();
            $table->date("datefin")->nullable();
            $table->string('numerocompte')->nullable();

            $table->unsignedBigInteger('entite_id')->nullable(true);
            $table->foreign('entite_id')->references('id')->on('entites')->onDelete('cascade');

            App\Outil::listenerUsers($table);
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
        Schema::dropIfExists('infobancaires');
    }
}
