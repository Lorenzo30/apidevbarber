<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("avatar")->default("default.png");
            $table->string("email")->unique();
            $table->string("password");
        });

        Schema::create('usersFavorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_barber');
        });

        Schema::create('usersappoiments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_barber');
            $table->datetime("ap_datetime");
        });

        Schema::create('barbers', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("string")->default("default.png");
            $table->float("stars")->default(0);
            $table->string("latitude")->nullable();
            $table->string("longitude")->nullable();
        });

        Schema::create('barbersphotos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barber');
            $table->string("url_photo");
        });

        Schema::create('barbereviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barber');
            $table->float("rate");
        });

        Schema::create('barberservices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barber');
            $table->string("name");
            $table->float("price");
        });

        Schema::create('barbertestimonials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barber');
            $table->string("name");
            $table->float("rate");
            $table->string("body");
        });

        Schema::create('barberavailabity', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barber');
            $table->integer("weekday");
            $table->string("hours");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('usersFavorites');
        Schema::dropIfExists('usersappoiments');
        Schema::dropIfExists('barbers');
        Schema::dropIfExists('barbersphotos');
        Schema::dropIfExists('barbereviews');
        Schema::dropIfExists('barberservices');
        Schema::dropIfExists('barbertestimonials');
        Schema::dropIfExists('barberavailabity');
    }
};
