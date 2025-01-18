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
        if(!Schema::hasTable('activaciones_notas')){

            Schema::create('activaciones_notas', function (Blueprint $table) {
                $table->id();
                $table->dateTime('fecha_inicio');
                $table->dateTime('fecha_fin');
                $table->integer('tipo_nota_id');
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
        Schema::dropIfExists('activaciones_notas');
    }
};
