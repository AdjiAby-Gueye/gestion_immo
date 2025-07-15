<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturelocationperiodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturelocationperiodes', function (Blueprint $table) {
            $table->id();
            $table->integer('montant')->nullable();

            $table->unsignedBigInteger('facturelocation_id')->nullable(true);
            $table->foreign('facturelocation_id')->references('id')->on('facturelocations')->onDelete('cascade');

            $table->unsignedBigInteger('periode_id')->nullable(true);
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('cascade');

            $table->date('date');
            $table->unsignedBigInteger('typefacture_id')->nullable(true);
            $table->foreign('typefacture_id')->references('id')->on('typefactures')->onDelete('cascade');

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
        Schema::dropIfExists('facturelocationperiodes');
    }
}
