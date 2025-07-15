<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopreneursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copreneurs', function (Blueprint $table) {
            $table->id();

            $table->string('nom')->nullable(true);
            $table->string('prenom')->nullable(true);
            $table->string('situationfamiliale')->nullable(true);
            $table->string('codepostal')->nullable(true);
            $table->string('pays')->nullable(true);
            $table->string('ville')->nullable(true);
            $table->string('nationalite')->nullable(true);
            $table->date('datenaissance')->nullable(true);
            $table->string('lieunaissance')->nullable(true);
            $table->string('adresse')->nullable(true);
            $table->string('profession')->nullable(true);
            $table->string('njf')->nullable(true);
            $table->string('telephone1')->nullable(true);
            $table->string('telephone2')->nullable(true);
            $table->string('cni')->nullable(true);
            $table->string('passport')->nullable(true);
            $table->string('email')->unique()->nullable(true);
            $table->unsignedBigInteger('locataire_id')->nullable();
            $table->foreign('locataire_id')->references('id')->on('locataires')->onDelete('cascade');
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
        Schema::dropIfExists('copreneurs');
    }
}
