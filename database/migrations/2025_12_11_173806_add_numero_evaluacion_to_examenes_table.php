<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumeroEvaluacionToExamenesTable extends Migration
{
    public function up()
    {
        Schema::table('examenes', function (Blueprint $table) {
            $table->integer('numero_evaluacion')->default(1)->after('lapso'); // Evaluación 1, 2, 3, etc.
        });
    }

    public function down()
    {
        Schema::table('examenes', function (Blueprint $table) {
            $table->dropColumn('numero_evaluacion');
        });
    }
}
