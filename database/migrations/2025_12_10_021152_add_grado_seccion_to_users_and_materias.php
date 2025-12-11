<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('grado', 20)->nullable()->after('estado'); // Examples: '1er Año', '2do Año'
            $table->string('seccion', 5)->nullable()->after('grado'); // Examples: 'A', 'B'
        });

        Schema::table('materias', function (Blueprint $table) {
            $table->string('grado', 20)->nullable()->after('cupo_maximo');
            $table->string('seccion', 5)->nullable()->after('grado');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['grado', 'seccion']);
        });

        Schema::table('materias', function (Blueprint $table) {
            $table->dropColumn(['grado', 'seccion']);
        });
    }
};
