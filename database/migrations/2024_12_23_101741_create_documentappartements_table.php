<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentappartementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentappartements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('document')->nullable();
            $table->string('nom')->nullable();

            $table->unsignedBigInteger('appartement_id')->nullable(true);
            $table->foreign('appartement_id')->references('id')->on('appartements')->onDelete('cascade');

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
        Schema::dropIfExists('documentappartements');
    }
}
