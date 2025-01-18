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
        if(!Schema::hasTable('descripciones_escalas')){

            Schema::create('descripciones_escalas', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->string('descripcion');
                $table->integer('equivalencia_id');
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
        Schema::dropIfExists('descripciones_escalas');
    }
};
