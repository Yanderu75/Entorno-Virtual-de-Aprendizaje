<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLapsoToExamenesTable extends Migration
{
    public function up()
    {
        Schema::table('examenes', function (Blueprint $table) {
            $table->integer('lapso')->default(1)->after('id_materia'); // 1, 2 o 3
        });
    }

    public function down()
    {
        Schema::table('examenes', function (Blueprint $table) {
            $table->dropColumn('lapso');
        });
    }
}
