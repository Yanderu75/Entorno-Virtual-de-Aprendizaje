<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamenesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Tabla Examenes
        Schema::create('examenes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_materia');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->boolean('publicado')->default(false);
            $table->timestamps();

            $table->foreign('id_materia')->references('id_materia')->on('materias')->onDelete('cascade');
        });

        // 2. Tabla Preguntas
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_examen');
            $table->text('enunciado');
            $table->enum('tipo', ['opcion_simple', 'verdadero_falso', 'abierta']); 
            $table->decimal('puntaje', 5, 2); // Ejemplo: 2.50
            $table->json('opciones')->nullable(); // Para cerradas: ["Opción A", "Opción B"]
            $table->text('respuesta_correcta')->nullable(); // Para cerradas: "Opción A" O "Verdadero"
            $table->timestamps();

            $table->foreign('id_examen')->references('id')->on('examenes')->onDelete('cascade');
        });

        // 3. Tabla Intentos (Cuando el estudiante presenta)
        Schema::create('intentos_examen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_examen');
            $table->unsignedBigInteger('id_estudiante'); // User ID
            $table->decimal('nota_final', 5, 2)->nullable();
            $table->boolean('correccion_docente')->default(false); // True si ya el profe revisó las abiertas
            $table->timestamp('fecha_entregado')->nullable();
            $table->timestamps();

            $table->foreign('id_examen')->references('id')->on('examenes')->onDelete('cascade');
            $table->foreign('id_estudiante')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });

        // 4. Tabla Respuestas (Detalle del intento)
        Schema::create('respuestas_examen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_intento');
            $table->unsignedBigInteger('id_pregunta');
            $table->text('respuesta_texto')->nullable(); // Lo que escribió o seleccionó el estudiante
            $table->decimal('puntaje_obtenido', 5, 2)->default(0); 
            $table->timestamps();

            $table->foreign('id_intento')->references('id')->on('intentos_examen')->onDelete('cascade');
            $table->foreign('id_pregunta')->references('id')->on('preguntas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('respuestas_examen');
        Schema::dropIfExists('intentos_examen');
        Schema::dropIfExists('preguntas');
        Schema::dropIfExists('examenes');
    }
}
