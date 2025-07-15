<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNiveauappartementInCompositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compositions', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('niveauappartement_id')->nullable();
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
        Schema::table('compositions', function (Blueprint $table) {
            //
            $table->dropForeign(['niveauappartement_id']);
            $table->dropColumn('niveauappartement_id');
        });
    }
}
