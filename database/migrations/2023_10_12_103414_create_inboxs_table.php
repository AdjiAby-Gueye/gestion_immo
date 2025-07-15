<?php

use App\Outil;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInboxsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inboxs', function (Blueprint $table) {
            $table->id();
            $table->string("subject")->nullable(true);
            $table->longText("body")->nullable(true);
            $table->string("sender_email")->nullable(true);
            $table->unsignedBigInteger("user_id")->nullable(true);
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->unsignedBigInteger("locataire_id")->nullable(true);
            $table->foreign("locataire_id")->references("id")->on("locataires")->onDelete("cascade");
            Outil::statusOfObject($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inboxs');
    }
}
