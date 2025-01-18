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
        if(!Schema::hasTable('escalas')){

            Schema::create('escalas', function (Blueprint $table) {
                $table->id();
                $table->integer('escala_cuantitativa');
                $table->string('escala_cualitativa');
                $table->text('descripcion');
                $table->integer('periodo_id');
                $table->integer('descripcion_escala_id');
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
        Schema::dropIfExists('escalas');
    }
};