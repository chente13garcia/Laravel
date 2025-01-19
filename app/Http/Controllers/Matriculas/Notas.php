<?php

namespace App\Http\Controllers\Matriculas;

use App\Http\Controllers\Controller;
use App\Models\Matriculas\Nota;
use Illuminate\Http\Request;
use App\Http\Requests\Matriculas\NotaStoreRequest;


class Notas extends Controller
{
    /**
     * Devuelve el data de la Tabla notas.
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

        $notas = Nota::query();

        # Filtros basicos, orden y paginacion
        $notas = $notas->where(function ($q) use ($query) {
            $q->where('estudiante_id', 'like', "%$query%");
            $q->orWhere('asignatura_id', 'like', "%$query%");
            $q->orWhere('id_periodo', 'like', "%$query%");
            $q->orWhere('docente_id', 'like', "%$query%");
            $q->orWhere('escala_cualitativa', 'like', "%$query%");
            $q->orWhere('escala_cuantitativa', 'like', "%$query%");
            $q->orWhere('escala_id', 'like', "%$query%");
            $q->orWhere('nota_id', 'like', "%$query%");
            $q->orWhere('etapa', 'like', "%$query%");
            $q->orWhere('nota', 'like', "%$query%");
            $q->orWhere('tipo_nota_id', 'like', "%$query%");
            $q->orWhere('observaciones', 'like', "%$query%");
        })
        ->orderBy($sortBy, $sortDesc === 'desc' ? 'desc' : 'asc')
        ->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json([
            'status' => true,
            'items' => $notas->items(),
            'total' => $notas->total(),
            'msg' => 'Notas: Información'
        ]);
    }

    /**
     * Devuelve opciones de Notas para dropdown.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dropdownOptions(Request $request)
    {
        $notas = Notas::select(
            "id", 
            "estudiante_id",
            "asignatura_id",
            "id_periodo",
            "docente_id",
            "escala_cualitativa",
            "escala_cuantitativa",
            "escala_id",
            "nota_id",
            "etapa",
            "nota",
            "tipo_nota_id",
            "observaciones" 
        );

        if ($request->has('periodo_id')) {
            $notas = $notas->where('periodo_id');
        }

        $notas = $notas->get();

        return response()->json([
            'status' => true,
            'items' => $notas,
            'msg' => 'Notas: Información'
        ]);
    }

    /**
     * Devuelve un item de la Tabla notas.
     */
    public function show(Nota $nota)
    {

        if (!$nota) {
            return response()->json([
                'status' => false,
                'msg' => 'Notas no existe'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $nota,
            'msg' => 'Notas'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NotaStoreRequest $request)
    {

        DB::beginTransaction();
        try {

            $nota = new Nota($request->all());
            $nota->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $nota,
                'msg' => "Notas: {$nota->nombre}"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'data' => $e->getMessage(),
                'msg' => 'Error al crear Notas!'
            ]);
        }
    }

    /**
     * Actualiza un registro de la Tabla notas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matriculas\Nota $nota
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Notas $nota)
    {
        DB::beginTransaction();
        try {

            $nota->fill($request->all());
            $nota->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $nota,
                'msg' => 'Notas: '. $nota->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al actualizar el Notas',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla notas.
     */
    public function destroy(Notas $nota)
    {
        DB::beginTransaction();
        try {

            $nota->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $nota,
                'msg' => 'Notas: '. $nota->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al eliminar la Notas',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla notas
     *
     * @param  mixed $tipo
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($nota)
    {
        DB::beginTransaction();
        try {
            
            $nota = Nota::withTrashed()->find($nota);
            $nota->restore();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $nota,
                'msg' => 'Notas: ' .$nota->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al restaurar Notas',
            ]);
        }
    }
}