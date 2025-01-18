<?php

namespace App\Models\Matriculas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TipoAporte extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table ='descripciones_escalas';

    /**
     * Atributos de la tabla descripciones_escalas.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'id',
        'nombre',
        'descripcion',
        'equivalencia_id'
    ];
    

}