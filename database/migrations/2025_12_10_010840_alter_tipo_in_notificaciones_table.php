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
        Schema::table('notificaciones', function (Blueprint $table) {
            // Drop the enum column and recreate as string or modify
            // MySQL approach to allow any string
            $table->string('tipo')->change();
        });
    }

    public function down(): void
    {
        Schema::table('notificaciones', function (Blueprint $table) {
             // Revert to enum if needed, but risky if data exists. 
             // Ideally we just leave it as string or define enum again.
             // For dev stability, let's keep it simple.
        });
    }
};
