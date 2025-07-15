<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annexes', function (Blueprint $table) {

            $table->id();

            $table->string('filename')->nullable(true);

            $table->string('numero')->nullable(true);

            $table->string('filepath')->nullable(true);

            $table->unsignedBigInteger('contrat_id')->nullable(true);

            $table->foreign('contrat_id')->references('id')->on('contrats')->onDelete('cascade');

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
        Schema::dropIfExists('annexes');
    }
}
