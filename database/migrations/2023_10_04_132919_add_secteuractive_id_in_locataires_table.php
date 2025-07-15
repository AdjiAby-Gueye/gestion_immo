<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSecteuractiveIdInLocatairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locataires', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('secteuractivite_id')->nullable(true);
            $table->foreign('secteuractivite_id')->references('id')->on('secteuractivites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locataires', function (Blueprint $table) {
            //
            $table->dropForeign(['secteuractivite_id']);
            $table->dropColumn('secteuractivite_id');
        });
    }
}
