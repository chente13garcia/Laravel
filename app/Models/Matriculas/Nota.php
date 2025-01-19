<?php

namespace App\Models\Matriculas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Nota extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table ='notas';

    /**
     * Atributos de la tabla notas.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'id',
        'estudiante_id',
        'asignatura_id',
        'id_periodo',
        'docente_id',
        'escala_cualitativa',
        'escala_cuantitativa',
        'escala_id',
        'nota_id',
        'etapa',
        'nota',
        'tipo_nota_id',
        'observaciones'
    ];
    

}