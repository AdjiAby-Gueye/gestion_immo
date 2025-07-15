<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsssInImmeublesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('immeubles', function (Blueprint $table) {
            $table->unsignedBigInteger('appartement_id')->nullable(true);
            $table->foreign('appartement_id')->references('id')->on('appartements')->onDelete('cascade');
            $table->unsignedBigInteger('questionnaire_id')->nullable(true);
            $table->foreign('questionnaire_id')->references('id')->on('questionnaires')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('immeubles', function (Blueprint $table) {
            $table->dropForeign(['appartement_id']);
            $table->dropColumn('appartement_id');
            $table->dropForeign(['questionnaire_id']);
            $table->dropColumn('questionnaire_id');
        });
    }
}
