<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQrCheckinToGuestsTable extends Migration
{
    public function up()
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->string('qr_code', 16)->nullable()->unique()->after('slug');
            $table->timestamp('checked_in_at')->nullable()->after('qr_code');
        });
    }

    public function down()
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['qr_code', 'checked_in_at']);
        });
    }
}
