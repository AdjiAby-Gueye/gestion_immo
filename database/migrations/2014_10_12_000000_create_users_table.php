<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('last_login_ip')->nullable(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('last_login')->nullable(1);
            $table->string('password');
            $table->string('image');
            $table->integer('active');
            \App\Outil::statusOfObject($table);
            $table->string("uploadsignature")->nullable(true);
            $table->string('matricule')->nullable(true);
            $table->string('telephone')->nullable(true);
            $table->string('adresse')->nullable(true);
            $table->rememberToken();
            $table->string('profil')->nullable(true);
            $table->string('tokennotifs')->nullable();
            $table->string('token')->nullable();

            $table->timestamps();
            \App\Outil::listenerUsers($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
