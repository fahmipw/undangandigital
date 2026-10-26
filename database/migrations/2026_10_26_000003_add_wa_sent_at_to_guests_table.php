<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWaSentAtToGuestsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('guests', 'wa_sent_at')) {
            Schema::table('guests', function (Blueprint $table) {
                $table->timestamp('wa_sent_at')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('guests', 'wa_sent_at')) {
            Schema::table('guests', function (Blueprint $table) {
                $table->dropColumn('wa_sent_at');
            });
        }
    }
}
