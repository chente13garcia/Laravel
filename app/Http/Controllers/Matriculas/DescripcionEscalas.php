<?php

namespace App\Http\Controllers\Matriculas;

use App\Http\Controllers\Controller;
use App\Models\Matriculas\DescripcionEscala;
use Illuminate\Http\Request;
use App\Http\Requests\Matriculas\DescripcionEscalaStoreRequest;


class DescripcionEscalas extends Controller
{
    /**
     * Devuelve el data de la Tabla descripciones_escalas
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

        $descripciones_escalas = DescripcionEscala::query();

        # Filtros basicos, orden y paginacion
        $descripciones_escalas = $descripciones_escalas->where(function ($q) use ($query) {
            $q->where('nombre', 'like', "%$query%");
            $q->orWhere('descripcion', 'like', "%$query%");
            $q->orWhere('equivalencia_id', 'like', "%$query%");
        })
        ->orderBy($sortBy, $sortDesc === 'desc' ? 'desc' : 'asc')
        ->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json([
            'status' => true,
            'items' => $descripciones_escalas->items(),
            'total' => $descripciones_escalas->total(),
            'msg' => 'Descripciones Escalas: Información'
        ]);
    }

    /**
     * Devuelve opciones de Descripciones Escalas para dropdown.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dropdownOptions(Request $request)
    {
        $descripciones_escalas = DescripcionEscalas::select("id", "nombre", "descripcion", "equivalencia_id");

        if ($request->has('periodo_id')) {
            $descripciones_escalas = $descripciones_escalas->where('periodo_id');
        }

        $descripciones_escalas = $descripciones_escalas->get();

        return response()->json([
            'status' => true,
            'items' => $descripciones_escalas,
            'msg' => 'Descripciones Escalas: Información'
        ]);
    }

    /**
     * Devuelve un item de la Tabla descripciones_escalas
     */
    public function show(DescripcionEscala $descripcion_escala)
    {

        if (!$descripcion_escala) {
            return response()->json([
                'status' => false,
                'msg' => 'Descripciones Escalas no existe'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $descripcion_escala,
            'msg' => 'Descripción Escala'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DescripcionEscalaStoreRequest $request)
    {

        DB::beginTransaction();
        try {

            $descripcion_escala = new DescripcionEscala($request->all());
            $descripcion_escala->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $descripcion_escala,
                'msg' => "Descripciones Escalas: {$descripcion_escala->nombre}"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'data' => $e->getMessage(),
                'msg' => 'Error al crear Descripción Escala!'
            ]);
        }
    }

    /**
     * Actualiza un registro de la Tabla descripciones_escalas
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matriculas\DescripcionEscala $descripcion_escala
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, DescripcionEscalas $descripcion_escala)
    {
        DB::beginTransaction();
        try {

            $descripcion_escala->fill($request->all());
            $descripcion_escala->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $descripcion_escala,
                'msg' => 'Descripción Escala: '. $descripcion_escala->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al actualizar el Descripción Escala',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla descripciones_escalas
     */
    public function destroy(DescripcionEscalas $descripcion_escala)
    {
        DB::beginTransaction();
        try {

            $descripcion_escala->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $descripcion_escala,
                'msg' => 'Descripción Escala: '. $descripcion_escala->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al eliminar la Descripción Escala',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla descripciones_escalas
     *
     * @param  mixed $tipo
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($descripcion_escala)
    {
        DB::beginTransaction();
        try {
            
            $descripcion_escala = DescripcionEscala::withTrashed()->find($descripcion_escala);
            $descripcion_escala->restore();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $descripcion_escala,
                'msg' => 'Descripción Escala: ' .$descripcion_escala->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al restaurar Descripción Escala',
            ]);
        }
    }
}
