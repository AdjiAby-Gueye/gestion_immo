<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratproprietairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratproprietaires', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('descriptif')->nullable();
            $table->integer('commissionvaleur')->nullable();
            $table->integer('commissionpourcentage')->nullable();
            $table->integer('is_tva')->nullable();
            $table->integer('is_brs')->nullable();
            $table->integer('is_tlv')->nullable();

            $table->unsignedBigInteger('entite_id')->nullable();
            $table->foreign('entite_id')->references('id')->on('entites')->onDelete('cascade');
            $table->unsignedBigInteger('proprietaire_id')->nullable();
            $table->foreign('proprietaire_id')->references('id')->on('proprietaires')->onDelete('cascade');
            $table->unsignedBigInteger('modelcontrat_id')->nullable();
            $table->foreign('modelcontrat_id')->references('id')->on('modelcontrats')->onDelete('cascade');
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
        Schema::dropIfExists('contratproprietaires');
    }
}
