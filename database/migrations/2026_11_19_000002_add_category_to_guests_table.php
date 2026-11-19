<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryToGuestsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('guests', 'category')) {
            Schema::table('guests', function (Blueprint $table) {
                $table->string('category', 50)->nullable()->after('no_hp');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('guests', 'category')) {
            Schema::table('guests', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }
}
