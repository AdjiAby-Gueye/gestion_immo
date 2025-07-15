<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewInImmeublesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('immeubles', function (Blueprint $table) {

            $table->unsignedBigInteger('structureimmeuble_id')->nullable(true);
            $table->foreign('structureimmeuble_id')->references('id')->on('structureimmeubles')->onDelete('cascade');
            $table->unsignedBigInteger('gardien_id')->nullable(true);
            $table->foreign('gardien_id')->references('id')->on('gardiens')->onDelete('cascade');
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

            $table->dropForeign(['structureimmeuble_id']);
            $table->dropColumn('structureimmeuble_id');

            $table->dropForeign(['gardien_id']);
            $table->dropColumn('gardien_id');
        });
    }
}
