<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInDemanderesiliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demanderesiliations', function (Blueprint $table) {
            $table->unsignedBigInteger('delaipreavi_id')->nullable(true);
            $table->foreign('delaipreavi_id')->references('id')->on('delaipreavis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demanderesiliations', function (Blueprint $table) {
           //
            $table->dropForeign(['delaipreavi_id']);
            $table->dropColumn('delaipreavi_id');
        });
    }
}
