<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInAppartementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appartements', function (Blueprint $table) {

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
        Schema::table('appartements', function (Blueprint $table) {
            //
            $table->dropForeign(['questionnaire_id']);
            $table->dropColumn('questionnaire_id');
        });
    }
}
