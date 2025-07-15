<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ilots', function (Blueprint $table) {
            $table->id();
            $table->integer("numero")->unique()->nullable();
            $table->string("adresse")->nullable();
             $table->string("numerotitrefoncier")->nullable();
            $table->string("datetitrefoncier")->nullable();
            $table->string("adressetitrefoncier")->nullable();
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
        Schema::dropIfExists('ilots');
    }
}
