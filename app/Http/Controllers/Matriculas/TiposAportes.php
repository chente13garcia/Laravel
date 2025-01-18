<?php

namespace App\Http\Controllers\Matriculas;

use App\Http\Controllers\Controller;
use App\Models\Matriculas\TipoAporte;
use Illuminate\Http\Request;
use App\Http\Requests\Matriculas\TipoAporteStoreRequest;


class TipoAportes extends Controller
{
    /**
     * Devuelve el data de la Tabla tipos_aportes.
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

        $tipos_aportes = TipoAporte::query();

        # Filtros basicos, orden y paginacion
        $tipos_aportes = $tipos_aportes->where(function ($q) use ($query) {
            $q->where('nombre', 'like', "%$query%");
            $q->orWhere('descripcion', 'like', "%$query%");
            $q->orWhere('sigla', 'like', "%$query%");
            $q->orWhere('estado', 'like', "%$query%");
        })
        ->orderBy($sortBy, $sortDesc === 'desc' ? 'desc' : 'asc')
        ->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json([
            'status' => true,
            'items' => $tipos_aportes->items(),
            'total' => $tipos_aportes->total(),
            'msg' => 'Tipo Aportes: Información'
        ]);
    }

    /**
     * Devuelve opciones de tipo aportes para dropdown.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dropdownOptions(Request $request)
    {
        $tipos_aportes = TipoAportes::select("id", "nombre", "descripcion", "sigla", "estado");

        if ($request->has('periodo_id')) {
            $tipos_aportes = $tipos_aportes->where('periodo_id');
        }

        $tipos_aportes = $tipos_aportes->get();

        return response()->json([
            'status' => true,
            'items' => $tipos_aportes,
            'msg' => 'Tipo Aportes: Información'
        ]);
    }

    /**
     * Devuelve un item de la Tabla tipos_aportes.
     */
    public function show(TipoAporte $tipo_aporte)
    {

        if (!$tipo_aporte) {
            return response()->json([
                'status' => false,
                'msg' => 'Tipo Aportes no existe'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $tipo_aporte,
            'msg' => 'Tipo Aporte'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TipoAporteStoreRequest $request)
    {

        DB::beginTransaction();
        try {

            $tipo_aporte = new TipoAporte($request->all());
            $tipo_aporte->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $tipo_aporte,
                'msg' => "Tipo Aportes: {$tipo_aporte->nombre}"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'data' => $e->getMessage(),
                'msg' => 'Error al crear Tipo Aporte!'
            ]);
        }
    }

    /**
     * Actualiza un registro de la Tabla tipos_aportes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matriculas\TipoAporte $tipo_aporte
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, TipoAportes $tipo_aporte)
    {
        DB::beginTransaction();
        try {

            $tipo_aporte->fill($request->all());
            $tipo_aporte->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $tipo_aporte,
                'msg' => 'Tipo Aporte: '. $tipo_aporte->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al actualizar el Tipo Aporte',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla tipos_aportes.
     */
    public function destroy(TipoAportes $tipo_aporte)
    {
        DB::beginTransaction();
        try {

            $tipo_aporte->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $tipo_aporte,
                'msg' => 'Tipo Aporte: '. $tipo_aporte->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al eliminar la Tipo Aporte',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla tipo_aportes
     *
     * @param  mixed $tipo
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($tipo_aporte)
    {
        DB::beginTransaction();
        try {
            
            $tipo_aporte = TipoAporte::withTrashed()->find($tipo_aporte);
            $tipo_aporte->restore();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $tipo_aporte,
                'msg' => 'Tipo Aporte: ' .$tipo_aporte->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al restaurar Tipo Aporte',
            ]);
        }
    }
}