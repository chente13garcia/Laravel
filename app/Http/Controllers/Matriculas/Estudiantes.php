<?php

namespace App\Http\Controllers\Matriculas;

use App\Http\Controllers\Controller;
use App\Models\Matriculas\Estudiante;
use Illuminate\Http\Request;
use App\Http\Requests\Matriculas\EstudianteStoreRequest;


class Estudiantes extends Controller
{
    /**
     * Devuelve el data de la Tabla estudiantes.
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

        $estudiantes = Estudiante::query();

        # Filtros basicos, orden y paginacion
        $estudiantes = $estudiantes->where(function ($q) use ($query) {
            $q->where('primer_nombre', 'like', "%$query%");
            $q->orWhere('segundo_nombre', 'like', "%$query%");
            $q->orWhere('primer_apellido', 'like', "%$query%");
            $q->orWhere('segundo_apellido', 'like', "%$query%");
            $q->orWhere('fecha_nacimiento', 'like', "%$query%");
            $q->orWhere('sexo', 'like', "%$query%");
            $q->orWhere('institucion', 'like', "%$query%");
            $q->orWhere('estado', 'like', "%$query%");
        })
        ->orderBy($sortBy, $sortDesc === 'desc' ? 'desc' : 'asc')
        ->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json([
            'status' => true,
            'items' => $estudiantes->items(),
            'total' => $estudiantes->total(),
            'msg' => 'Estudiantes: Información'
        ]);
    }

    /**
     * Devuelve opciones de Estudiantes para dropdown.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dropdownOptions(Request $request)
    {
        $estudiantes = Estudiantes::select(
            "id", 
            "primer_nombre", 
            "segundo_nombre", 
            "primer_apellido",
            "segundo_apellido",
            "fecha_nacimiento",
            "sexo",
            "institucion",            
            "estado"
        );

        if ($request->has('periodo_id')) {
            $estudiantes = $estudiantes->where('periodo_id');
        }

        $estudiantes = $estudiantes->get();

        return response()->json([
            'status' => true,
            'items' => $estudiantes,
            'msg' => 'Estudiantes: Información'
        ]);
    }

    /**
     * Devuelve un item de la Tabla estudiantes.
     */
    public function show(Estudiante $estudiante)
    {

        if (!$estudiante) {
            return response()->json([
                'status' => false,
                'msg' => 'Estudiante no existe'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $estudiante,
            'msg' => 'Estudiante'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EstudianteStoreRequest $request)
    {

        DB::beginTransaction();
        try {

            $estudiante = new Estudiante($request->all());
            $estudiante->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $estudiante,
                'msg' => "Estudiantes: {$estudiante->nombre}"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'data' => $e->getMessage(),
                'msg' => 'Error al crear Estudiante!'
            ]);
        }
    }

    /**
     * Actualiza un registro de la Tabla estudiantes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matriculas\Estudiante $estudiante
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Estudiantes $estudiante)
    {
        DB::beginTransaction();
        try {

            $estudiante->fill($request->all());
            $estudiante->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $estudiante,
                'msg' => 'Estudiante: '. $estudiante->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al actualizar el Estudiante',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla estudiantes.
     */
    public function destroy(Estudiantes $estudiante)
    {
        DB::beginTransaction();
        try {

            $estudiante->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $estudiante,
                'msg' => 'Estudiante: '. $estudiante->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al eliminar la Estudiante',
            ]);
        }
    }

    /**
     * Restaura un registro eliminado de la tabla estudiantes
     *
     * @param  mixed $tipo
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($estudiante)
    {
        DB::beginTransaction();
        try {
            
            $estudiante = Estudiante::withTrashed()->find($estudiante);
            $estudiante->restore();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $estudiante,
                'msg' => 'Estudiante: ' .$estudiante->nombre
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'msg' => 'Error al restaurar Estudiante',
            ]);
        }
    }
}
