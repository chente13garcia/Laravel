<?php

namespace App\Models\Matriculas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Escala extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table ='escalas';

    /**
     * Atributos de la tabla tipo_aportes.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'id',
        'escala_cuantitativa',
        'escala_cualitativa',
        'descripcion',
        'periodo_id',
        'descripcion_escala_id',
    ];
    

}