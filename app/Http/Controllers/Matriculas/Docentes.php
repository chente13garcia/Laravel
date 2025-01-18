<?php

namespace App\Http\Controllers\Matriculas;

use App\Http\Controllers\Controller;
use App\Models\Matriculas\Docente;
use Illuminate\Http\Request;
use App\Http\Requests\Matriculas\DocenteStoreRequest;

class Docentes extends Controller
{
    /**
     * Devuelve el data de la Tabla docentes.
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

        $docentes = Docente::query();

        # Filtros basicos, orden y paginacion
        $docentes = $docentes->where(function ($q) use ($query) {
            $q->where('primer_nombre', 'like', "%$query%");
            $q->orWhere('segundo_nombre', 'like', "%$query%");
            $q->where('primer_apellido', 'like', "%$query%");
            $q->orWhere('segundo_apellido', 'like', "%$query%");
            $q->orWhere('fecha_nacimiento', 'like', "%$query%");
            $q->orWhere('sexo', 'like', "%$query%");
            $q->orWhere('estado', 'like', "%$query%");
        })
        ->orderBy($sortBy, $sortDesc === 'desc' ? 'desc' : 'asc')
        ->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json([
            'status' => true,
            'items' => $docentes->items(),
            'total' => $docentes->total(),
            'msg' => 'Docentes: Información'
        ]);
    }

    /**
     * Devuelve opciones de Docentes para dropdown.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dropdownOptions(Request $request)
    {
        $docentes = Docentes::select("id", "primer_nombre", "segundo_nombre", "primer_apellido", "segundo_apellido", "fecha_nacimiento", "sexo", "estado");

        if ($request->has('periodo_id')) {
            $docentes = $docentes->where('periodo_id');
        }

        $docentes = $docentes->get();

        return response()->json([
            'status' => true,
            'items' => $docentes,
            'msg' => 'Docentes: Información'
        ]);
    }

    /**
     * Devuelve un item de la Tabla docentes.
     */
    public function show(Docente $docente)
    {

        if (!$docente) {
            return response()->json([
                'status' => false,
                'msg' => 'Docentes no existe'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $docente,
            'msg' => 'Docente'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DocenteStoreRequest $request)
    {

        DB::beginTransaction();
        try {

            $docente = new Docente($request->all());
            $docente->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $docente,
                'msg' => "Docentes: {$docente->nombre}"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'data' => $e->getMessage(),
                'msg' => 'Error al crear Docente!'
            ]);
        }
    }

    /**
     * Actualiza un registro de la Tabla docentes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matriculas\Docente $docente
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Docentes $docente)
    {
        DB::beginTransaction();
        try {

            $docente->fill($request->all());
            $docente->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $docente,
                'msg' => 'Docente: '. $docente->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al actualizar el Docente',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla docentes.
     */
    public function destroy(Docentes $docente)
    {
        DB::beginTransaction();
        try {

            $docente->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $docente,
                'msg' => 'Docente: '. $docente->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al eliminar la Docente',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla docentes
     *
     * @param  mixed $tipo
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($docente)
    {
        DB::beginTransaction();
        try {
            
            $docente = Docente::withTrashed()->find($docente);
            $docente->restore();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $docente,
                'msg' => 'Docente: ' .$docente->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al restaurar Docente',
            ]);
        }
    }
}
