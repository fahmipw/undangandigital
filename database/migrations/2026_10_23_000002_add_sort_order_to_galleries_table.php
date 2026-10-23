<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSortOrderToGalleriesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('galleries', 'sort_order')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->unsignedInteger('sort_order')->default(0)->after('image_path');
            });
        }

        // Isi urutan awal mengikuti created_at per undangan
        $invIds = DB::table('galleries')->distinct()->pluck('invitation_id');
        foreach ($invIds as $invId) {
            $rows = DB::table('galleries')
                ->where('invitation_id', $invId)
                ->orderBy('created_at', 'ASC')
                ->orderBy('id', 'ASC')
                ->pluck('id');
            foreach ($rows as $i => $id) {
                DB::table('galleries')->where('id', $id)->update(['sort_order' => $i]);
            }
        }
    }

    public function down()
    {
        if (Schema::hasColumn('galleries', 'sort_order')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
    }
}
