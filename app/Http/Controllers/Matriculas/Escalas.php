<?php

namespace App\Http\Controllers\Matriculas;

use App\Http\Controllers\Controller;
use App\Models\Matriculas\Escala;
use Illuminate\Http\Request;
use App\Http\Requests\Matriculas\EscalaStoreRequest;


class Escalas extends Controller
{
    /**
     * Devuelve el data de la Tabla escalas .
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

        $escalas  = Escala::query();

        # Filtros basicos, orden y paginacion
        $escalas  = $escalas ->where(function ($q) use ($query) {
            $q->where('escala_cuantitativa', 'like', "%$query%");
            $q->orWhere('escala_cualitativa', 'like', "%$query%");
            $q->orWhere('descripcion', 'like', "%$query%");
            $q->orWhere('periodo_id', 'like', "%$query%");
            $q->orWhere('descripcion_escala_id', 'like', "%$query%");
        })
        ->orderBy($sortBy, $sortDesc === 'desc' ? 'desc' : 'asc')
        ->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json([
            'status' => true,
            'items' => $escalas ->items(),
            'total' => $escalas ->total(),
            'msg' => 'Escala: Información'
        ]);
    }

    /**
     * Devuelve opciones de Escala para dropdown.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dropdownOptions(Request $request)
    {
        $escalas  = Escalas::select("id", "escala_cuantitativa", "escala_cualitativa", "descripcion", "periodo_id", "descripcion_escala_id");

        if ($request->has('periodo_id')) {
            $escalas  = $escalas ->where('periodo_id');
        }

        $escalas  = $escalas ->get();

        return response()->json([
            'status' => true,
            'items' => $escalas ,
            'msg' => 'Escala: Información'
        ]);
    }

    /**
     * Devuelve un item de la Tabla escalas .
     */
    public function show(Escala $escala)
    {

        if (!$escala) {
            return response()->json([
                'status' => false,
                'msg' => 'Escala no existe'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $escala,
            'msg' => 'Escala'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EscalaStoreRequest $request)
    {

        DB::beginTransaction();
        try {

            $escala = new Escala($request->all());
            $escala->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $escala,
                'msg' => "Escala: {$escala->nombre}"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'data' => $e->getMessage(),
                'msg' => 'Error al crear Escala!'
            ]);
        }
    }

    /**
     * Actualiza un registro de la Tabla escalas .
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matriculas\Escala $escala
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Escalas $escala)
    {
        DB::beginTransaction();
        try {

            $escala->fill($request->all());
            $escala->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $escala,
                'msg' => 'Escala: '. $escala->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al actualizar el Escala',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla escalas .
     */
    public function destroy(Escalas $escala)
    {
        DB::beginTransaction();
        try {

            $escala->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $escala,
                'msg' => 'Escala: '. $escala->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al eliminar la Escala',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla escalas
     *
     * @param  mixed $tipo
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($escala)
    {
        DB::beginTransaction();
        try {
            
            $escala = Escala::withTrashed()->find($escala);
            $escala->restore();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $escala,
                'msg' => 'Escala: ' .$escala->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al restaurar Escala',
            ]);
        }
    }
}
