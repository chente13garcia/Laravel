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
        if(!Schema::hasTable('notas')){

            Schema::create('notas', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('estudiante_id');
                $table->bigInteger('asignatura_id');
                $table->bigInteger('id_periodo');
                $table->bigInteger('docente_id');
                $table->string('escala_cualitativa');
                $table->decimal('escala_cuantitativa', 8, 2);
                $table->bigInteger('escala_id');
                $table->bigInteger('tipo_aporte_id');
                $table->bigInteger('etapa');
                $table->decimal('nota', 8, 2);
                $table->bigInteger('tipo_nota_id');
                $table->string('observaciones');
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
        Schema::dropIfExists('notas');
    }
};
