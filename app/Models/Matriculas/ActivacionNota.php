<?php

namespace App\Models\Matriculas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ActivacionNota extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table ='activacion_notas';

    /**
     * Atributos de la tabla activacion_notas.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'id',
        'fecha_inicio',
        'fecha_fin',
        'tipo_nota_id'
    ];
    

}
