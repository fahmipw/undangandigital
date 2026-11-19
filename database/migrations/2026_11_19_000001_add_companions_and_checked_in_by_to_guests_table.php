<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanionsAndCheckedInByToGuestsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('guests', 'companions')) {
            Schema::table('guests', function (Blueprint $table) {
                $table->unsignedTinyInteger('companions')->default(0)->after('checked_in_at');
            });
        }
        if (!Schema::hasColumn('guests', 'checked_in_by')) {
            Schema::table('guests', function (Blueprint $table) {
                $table->string('checked_in_by', 100)->nullable()->after('companions');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('guests', 'checked_in_by')) {
            Schema::table('guests', function (Blueprint $table) {
                $table->dropColumn('checked_in_by');
            });
        }
        if (Schema::hasColumn('guests', 'companions')) {
            Schema::table('guests', function (Blueprint $table) {
                $table->dropColumn('companions');
            });
        }
    }
}
