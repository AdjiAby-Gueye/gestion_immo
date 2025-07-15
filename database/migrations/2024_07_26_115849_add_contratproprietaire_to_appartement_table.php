<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContratproprietaireToAppartementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appartements', function (Blueprint $table) {
            $table->unsignedBigInteger('contratproprietaire_id')->nullable();
            $table->foreign('contratproprietaire_id')->references('id')->on('contratproprietaires')->onDelete('cascade');
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
            //
            $table->dropForeign(['contratproprietaire_id']);
            $table->dropColumn('contratproprietaire_id');
        });
    }
}
