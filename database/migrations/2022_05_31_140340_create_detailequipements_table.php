<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailequipementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detailequipements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('commentaire')->nullable();

            $table->unsignedBigInteger('equipementpiece_id')->nullable(true);
            $table->foreign('equipementpiece_id')->references('id')->on('equipementpieces')->onDelete('cascade');
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
        Schema::dropIfExists('detailequipements');
    }
}
