<?php

namespace App\Http\Controllers\Matriculas;

use App\Http\Controllers\Controller;
use App\Models\Matriculas\ActivacionNota;
use Illuminate\Http\Request;
use App\Http\Requests\Matriculas\ActivacionNotaStoreRequest;

class ActivacionNotas extends Controller
{
    /**
     * Devuelve el data de la Tabla activacion_notas.
     * @return \Illuminate\Http\JsonResponse
     * @param  \Illuminate\Http\Request $request
     */
    public function index(Request $request)
    {
        # Parametros para filtros de front end
        $params = $request->input('options', []);
        $query = $request->input('q') != null ? $request->input('q') : null;
        $perPage = $params['itemsPerPage'] ?? 10;
        $sortBy = $params['sortBy'][0]['key'] ?? 'id';
        $sortDesc = $params['sortBy'][0]['order'] ?? 'desc';
        $page = $params['page'] ?? 1;

        $activacion_notas = ActivacionNota::query();

        # Filtros basicos, orden y paginacion
        $activacion_notas = $activacion_notas->where(function ($q) use ($query) {
            $q->where('fecha_inicio', 'like', "%$query%");
            $q->orWhere('fecha_fin', 'like', "%$query%");
            $q->orWhere('tipo_nota_id', 'like', "%$query%");
        })
        ->orderBy($sortBy, $sortDesc === 'desc' ? 'desc' : 'asc')
        ->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json([
            'status' => true,
            'items' => $activacion_notas->items(),
            'total' => $activacion_notas->total(),
            'msg' => 'Activación Notas: Información'
        ]);
    }

    /**
     * Devuelve opciones de activacion de notas para dropdown.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dropdownOptions(Request $request)
    {
        $activacion_notas = ActivacionNota::select("id", "fecha_inicio", "fecha_fin", "tipo_notas_id");

        // if ($request->has('periodo_id')) {
        //     $activacion_notas = $activacion_notas->where('periodo_id');
        // }

        $activacion_notas = $activacion_notas->get();

        return response()->json([
            'status' => true,
            'items' => $activacion_notas,
            'msg' => 'Activación Notas: Información'
        ]);
    }

    /**
     * Devuelve un item de la Tabla tipos_notas.
     */
    public function show(ActivacionNota $activacion_nota)
    {

        if (!$activacion_nota) {
            return response()->json([
                'status' => false,
                'msg' => 'Activación Nota no existe'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $activacion_nota,
            'msg' => 'Activación Nota'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ActivacionNotaStoreRequest $request)
    {

        DB::beginTransaction();
        try {

            $activacion_nota = new ActivacionNota($request->all());
            $activacion_nota->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $activacion_nota,
                'msg' => "Activación Nota: {$activacion_nota->nombre}"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'data' => $e->getMessage(),
                'msg' => 'Error al crear Activación nota!'
            ]);
        }
    }

    /**
     * Actualiza un registro de la Tabla activacion_notas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matriculas\ActivacionNota $activacion_nota
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ActivacionNota $activacion_nota)
    {
        DB::beginTransaction();
        try {

            $activacion_nota->fill($request->all());
            $activacion_nota->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $activacion_nota,
                'msg' => 'Activación Nota: '. $activacion_nota->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al actualizar la Activación Nota',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla activacion_notas.
     */
    public function destroy(ActivacionNota $activacion_nota)
    {
        DB::beginTransaction();
        try {

            $activacion_nota->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $activacion_nota,
                'msg' => 'Activación Nota: '. $activacion_nota->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al eliminar la Activación Nota',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla activacion_notas
     *
     * @param  mixed $tipo
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($tipo_nota)
    {
        DB::beginTransaction();
        try {
            
            $tipo_nota = TipoNota::withTrashed()->find($tipo_nota);
            $tipo_nota->restore();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $tipo_nota,
                'msg' => 'Tipo Nota: ' .$tipo_nota->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al restaurar Tipo Nota',
            ]);
        }
    }
}