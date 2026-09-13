<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->foreignId('invitation_id')->constrained('invitations')->onDelete('cascade');
            $table->string('key_name', 100);
            $table->text('key_value');
            $table->primary(['invitation_id', 'key_name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
