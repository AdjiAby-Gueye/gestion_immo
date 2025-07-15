<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInContratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrats', function (Blueprint $table) {
            $table->unsignedBigInteger('demanderesiliation_id')->nullable(true);
            $table->foreign('demanderesiliation_id')->references('id')->on('demanderesiliations')->onDelete('cascade');
            $table->unsignedBigInteger('caution_id')->nullable(true);
            $table->foreign('caution_id')->references('id')->on('cautions')->onDelete('cascade');
            $table->string('etat')->nullable(true);
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
            //
            $table->dropForeign(['demanderesiliation_id']);
            $table->dropColumn('demanderesiliation_id');
            $table->dropForeign(['caution_id']);
            $table->dropColumn('caution_id');
            $table->dropColumn('etat');
            
        });
    }
}
