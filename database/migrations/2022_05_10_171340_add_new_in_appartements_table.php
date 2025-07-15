<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewInAppartementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appartements', function (Blueprint $table) {
            $table->unsignedBigInteger('niveauappartement_id')->nullable(true);
            $table->foreign('niveauappartement_id')->references('id')->on('niveauappartements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appartements', function (Blueprint $table) {
             $table->dropForeign(['niveauappartement_id']);
            $table->dropColumn('niveauappartement_id');
        });
    }
}
