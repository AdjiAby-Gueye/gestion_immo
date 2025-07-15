<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImmeublesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('immeubles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('equipegestion_id')->nullable(true);
            $table->foreign('equipegestion_id')->references('id')->on('equipegestions')->onDelete('cascade');
            $table->string('nombreappartement')->nullable(true);
            $table->string('nombregroupeelectrogene')->nullable(true);

            $table->string('nom');
            $table->string('adresse')->nullable(true);
            $table->string('nombreascenseur')->nullable(true);
            $table->string('nombrepiscine')->nullable(true);
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
        Schema::dropIfExists('immeubles');
    }
}
