<?php

namespace App\Http\Controllers\Matriculas;

use App\Http\Controllers\Controller;
use App\Models\Matriculas\TipoNota;
use Illuminate\Http\Request;
use App\Http\Requests\Matriculas\TipoNotaStoreRequest;

class TiposNotas extends Controller
{
    /**
     * Devuelve el data de la Tabla tipos_notas.
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

        $tipos_notas = TipoNota::query();

        # Filtros basicos, orden y paginacion
        $tipos_notas = $tipos_notas->where(function ($q) use ($query) {
            $q->where('nombre', 'like', "%$query%");
            $q->orWhere('descripcion', 'like', "%$query%");
            $q->orWhere('cantidad_etapas', 'like', "%$query%");
        })
        ->orderBy($sortBy, $sortDesc === 'desc' ? 'desc' : 'asc')
        ->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json([
            'status' => true,
            'items' => $tipos_notas->items(),
            'total' => $tipos_notas->total(),
            'msg' => 'Tipos Notas: Información'
        ]);
    }

    /**
     * Devuelve opciones de tipos de notas para dropdown.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dropdownOptions(Request $request)
    {
        $tipos_notas = TipoNota::select("id", "nombre", "descripcion", "cantidad_etapas", "estado");

        if ($request->has('periodo_id')) {
            $tipos_notas = $tipos_notas->where('periodo_id');
        }

        $tipos_notas = $tipos_notas->get();

        return response()->json([
            'status' => true,
            'items' => $tipos_notas,
            'msg' => 'Tipos Notas: Información'
        ]);
    }

    /**
     * Devuelve un item de la Tabla tipos_notas.
     */
    public function show(TipoNota $tipo_nota)
    {

        if (!$tipo_nota) {
            return response()->json([
                'status' => false,
                'message' => 'Tipo Nota no existe'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $tipo_nota,
            'message' => 'Tipo Nota'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TipoNotaStoreRequest $request)
    {

        DB::beginTransaction();
        try {

            $tipo_nota = new TipoNota($request->all());
            $tipo_nota->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $tipo_nota,
                'message' => "Tipo Nota: {$tipo_nota->nombre}"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'data' => $e->getMessage(),
                'message' => 'Error al crear departamento!'
            ]);
        }
    }

    /**
     * Actualiza un registro de la Tabla tipos_notas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matriculas\TipoNota $tipo_nota
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, TipoNota $tipo_nota)
    {
        DB::beginTransaction();
        try {

            $tipo_nota->fill($request->all());
            $tipo_nota->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $tipo_nota,
                'msg' => 'Tipo Nota: '. $tipo_nota->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al actualizar el Tipo Nota',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla tipos_notas.
     */
    public function destroy(TipoNota $tipo_nota)
    {
        DB::beginTransaction();
        try {

            $tipo_nota->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $tipo_nota,
                'msg' => 'Tipo Nota: '. $tipo_nota->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al eliminar el Tipo Nota',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla tipos_notas
     *
     * @param  mixed $tipo
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($asiento)
    {
        DB::beginTransaction();
        try {
            
            $asiento = Asiento::withTrashed()->find($asiento);
            $asiento->restore();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $asiento,
                'msg' => 'Asiento: ' .$asiento->sumilla
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al restaurar Asiento',
            ]);
        }
    }
}

