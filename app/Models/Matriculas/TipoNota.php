<?php

namespace App\Models\Matriculas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TipoNota extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table ='tipos_notas';

    /**
     * Atributos de la tabla tipos_notas.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'id',
        'nombre',
        'descripcion',
        'cantidad_etapas',
        'estado', # Para indentificar las etapas activac cuando cambia la normativa
    ];
    
}

