<?php

namespace App\Http\Controllers\Matriculas;

use App\Http\Controllers\Controller;
use App\Models\Matriculas\EquivalenciaEscala;
use Illuminate\Http\Request;
use App\Http\Requests\Matriculas\EquivalenciaEscalaStoreRequest;

class EquivalenciaEscalas extends Controller
{
    /**
     * Devuelve el data de la Tabla equivalencia_escalas
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

        $equivalencia_escalas = EquivalenciaEscala::query();

        # Filtros basicos, orden y paginacion
        $equivalencia_escalas = $equivalencia_escalas->where(function ($q) use ($query) {
            $q->where('nombre', 'like', "%$query%");
            $q->orWhere('descripcion', 'like', "%$query%");
            $q->orWhere('estado', 'like', "%$query%");
        })
        ->orderBy($sortBy, $sortDesc === 'desc' ? 'desc' : 'asc')
        ->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json([
            'status' => true,
            'items' => $equivalencia_escalas->items(),
            'total' => $equivalencia_escalas->total(),
            'msg' => 'Equivalencia Escalas: Información'
        ]);
    }

    /**
     * Devuelve opciones de equivalencias escalas para dropdown.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dropdownOptions(Request $request)
    {
        $equivalencia_escalas = EquivalenciaEscala::select("id", "nombre", "descripcion", "estado");

        if ($request->has('periodo_id')) {
            $equivalencia_escalas = $equivalencia_escalas->where('periodo_id');
        }

        $equivalencia_escalas = $equivalencia_escalas->get();

        return response()->json([
            'status' => true,
            'items' => $equivalencia_escalas,
            'msg' => 'Equivalencia Escalas: Información'
        ]);
    }

    /**
     * Devuelve un item de la Tabla equivalencia_escalas.
     */
    public function show(EquivalenciaEscala $equivalencia_escala)
    {

        if (!$equivalencia_escala) {
            return response()->json([
                'status' => false,
                'msg' => 'Equivalencia Escalas no existe'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $equivalencia_escala,
            'msg' => 'Equivalencia Escalas'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EquivalenciaEscalaStoreRequest $request)
    {

        DB::beginTransaction();
        try {

            $equivalencia_escala = new EquivalenciaEscala($request->all());
            $equivalencia_escala->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $equivalencia_escala,
                'msg' => "Equivalencia Escalas: {$equivalencia_escala->nombre}"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'data' => $e->getMessage(),
                'msg' => 'Error al crear Equivalencia Escalas!'
            ]);
        }
    }

    /**
     * Actualiza un registro de la Tabla equivalencia_escalas
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matriculas\EquivalenciaEscala $equivalencia_escala
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, EquivalenciaEscala $equivalencia_escala)
    {
        DB::beginTransaction();
        try {

            $equivalencia_escala->fill($request->all());
            $equivalencia_escala->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $equivalencia_escala,
                'msg' => 'Equivalencia Escalas: '. $equivalencia_escala->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al actualizar la Equivalencia Escalas',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla equivalencia_escalas
     */
    public function destroy(EquivalenciaEscala $equivalencia_escala)
    {
        DB::beginTransaction();
        try {

            $equivalencia_escala->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $equivalencia_escala,
                'msg' => 'Equivalencia Escalas: '. $equivalencia_escala->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al eliminar la Equivalencia Escalas',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla equivalencia_escalas
     *
     * @param  mixed $tipo
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($equivalencia_escala)
    {
        DB::beginTransaction();
        try {
            
            $equivalencia_escala = EquivalenciaEscala::withTrashed()->find($equivalencia_escala);
            $equivalencia_escala->restore();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $equivalencia_escala,
                'msg' => 'Equivalencia Escalas: ' .$equivalencia_escala->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al restaurar Equivalencia Escalas',
            ]);
        }
    }
}