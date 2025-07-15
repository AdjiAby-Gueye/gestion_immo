<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFraisupplementairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fraisupplementaires', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('avisecheance_id')->nullable(true);
            $table->foreign('avisecheance_id')->references('id')->on('avisecheances')->onDelete('cascade');

            $table->text('designation')->nullable();

            $table->string('frais')->nullable();

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
        Schema::dropIfExists('fraisupplementaires');
    }
}
