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
        if(!Schema::hasTable('estudiantes')){

            Schema::create('estudiantes', function (Blueprint $table) {
                $table->id();
                $table->string('primer_nombre');
                $table->string('segundo_nombre');
                $table->string('primer_apellido');
                $table->string('segundo_apellido');
                $table->dateTime('fecha_nacimiento');
                $table->enum('sexo', ['masculino', 'femenino']);
                $table->string('institucion');
                $table->boolean('estado')->default(true);
                $table->softDeletes();
                $table->timestamps();
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
