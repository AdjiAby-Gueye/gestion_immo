<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailconstituantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detailconstituants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('commentaire')->nullable();

            $table->unsignedBigInteger('constituantpiece_id')->nullable(true);
            $table->foreign('constituantpiece_id')->references('id')->on('constituantpieces')->onDelete('cascade');
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
        Schema::dropIfExists('detailconstituants');
    }
}
