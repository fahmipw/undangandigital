<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestsTable extends Migration
{
    public function up()
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained('invitations')->onDelete('cascade');
            $table->string('nama', 150);
            $table->string('no_hp', 20)->nullable();
            $table->string('slug', 150);
            // $table->timestamps(); if needed
        });
    }

    public function down()
    {
        Schema::dropIfExists('guests');
    }
}
