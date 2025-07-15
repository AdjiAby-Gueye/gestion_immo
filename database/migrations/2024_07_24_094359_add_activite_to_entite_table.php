<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiviteToEntiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entites', function (Blueprint $table) {
            $table->unsignedBigInteger('activite_id')->nullable();
            $table->foreign('activite_id')->references('id')->on('activites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entites', function (Blueprint $table) {
            $table->dropForeign(['activite_id']);
            $table->dropColumn('activite_id');
        });
    }
}
