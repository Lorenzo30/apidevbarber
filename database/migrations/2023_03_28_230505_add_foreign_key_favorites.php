<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


use App\Models\User;
use App\Models\Barber;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('usersfavorites', function (Blueprint $table) {
            $table->foreign('id_user')->references("id")->on("users");
            $table->foreign('id_barber')->references("id")->on("barbers");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
