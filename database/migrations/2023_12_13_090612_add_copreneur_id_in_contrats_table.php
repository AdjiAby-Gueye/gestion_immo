<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCopreneurIdInContratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrats', function (Blueprint $table) {
            //
            $table->unsignedBigInteger("copreneur_id")->nullable(true);
            $table->foreign("copreneur_id")->references("id")->on("copreneurs");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrats', function (Blueprint $table) {
            $table->dropForeign(['copreneur_id']);
            $table->dropColumn('copreneur_id');
        });
    }
}
