<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipegestionMembreequipegetionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipegestion_membreequipegestion', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('membreequipegestion_id');
            $table->foreign('membreequipegestion_id')->references('id')->on('membreequipegestions')->onDelete('cascade');
            $table->unsignedBigInteger('equipegestion_id');
            $table->foreign('equipegestion_id')->references('id')->on('equipegestions')->onDelete('cascade');
            $table->unsignedBigInteger('fonction_id')->nullable();
            $table->foreign('fonction_id')->references('id')->on('fonctions')->onDelete('cascade');
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
        Schema::dropIfExists('equipegestion_membreequipegestion');
    }
}
