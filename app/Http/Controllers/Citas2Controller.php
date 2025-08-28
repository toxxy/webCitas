<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Citas2;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
class Citas2Controller extends Controller
{
    /**
     * Obtener todas las citas con paginación
     */
    public function index(): JsonResponse
    {
        try {
            $citas = Citas2::orderBy('FechaProgramada', 'desc')->paginate(15);
            return response()->json([
                'success' => true,
                'message' => 'Citas obtenidas exitosamente',
                'data' => $citas->items(),
                'meta' => [
                    'current_page' => $citas->currentPage(),
                    'total' => $citas->total(),
                    'per_page' => $citas->perPage(),
                    'last_page' => $citas->lastPage()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las citas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear nueva cita
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'orden' => 'required|integer|unique:citas2s,orden',
                'descripcion' => 'required|string|max:500',
                'status' => 'required|string|in:Sin-Programar,Programada,Realizada,Cancelada',
                'FechaProgramada' => 'required|date|after_or_equal:today',
                'FechaRealizada' => 'nullable|date',
                'RFC' => 'required|string|min:12|max:13|regex:/^[A-Z]{4}[0-9]{6}[A-Z0-9]{3}$/',
                'Nombre' => 'required|string|max:255'
            ]);

            $cita = Citas2::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cita creada exitosamente',
                'data' => $cita
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la cita',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener cita por ID
     */
    public function show(string $id): JsonResponse
    {
        try {
            $cita = Citas2::find($id);
            
            if (!$cita) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cita no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cita obtenida exitosamente',
                'data' => $cita
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la cita',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     *     path="/api/Citas/{id}",
     *     summary="Actualizar cita",
     *     tags={"Citas"},
     *     security={{"sanctum":{}}},
     *         name="id",
     *         in="path",
     *         required=true,
     *     ),
     *         required=true,
     *     ),
     *         response=200,
     *         description="Cita actualizada exitosamente"
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $cita = Citas2::find($id);
            
            if (!$cita) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cita no encontrada'
                ], 404);
            }

            $validated = $request->validate([
                'orden' => 'sometimes|required|integer|unique:citas2s,orden,' . $id,
                'descripcion' => 'sometimes|required|string|max:500',
                'status' => 'sometimes|required|string|in:Sin-Programar,Programada,Realizada,Cancelada',
                'FechaProgramada' => 'sometimes|required|date',
                'FechaRealizada' => 'nullable|date',
                'RFC' => 'sometimes|required|string|min:12|max:13|regex:/^[A-Z]{4}[0-9]{6}[A-Z0-9]{3}$/',
                'Nombre' => 'sometimes|required|string|max:255'
            ]);

            $cita->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cita actualizada exitosamente',
                'data' => $cita->fresh()
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la cita',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     *     path="/api/Citas/{id}",
     *     summary="Eliminar cita",
     *     tags={"Citas"},
     *     security={{"sanctum":{}}},
     *         name="id",
     *         in="path",
     *         required=true,
     *     ),
     *         response=200,
     *         description="Cita eliminada exitosamente"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $cita = Citas2::find($id);
            
            if (!$cita) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cita no encontrada'
                ], 404);
            }

            $cita->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cita eliminada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la cita',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     *     path="/api/Citas/searchn/{name}",
     *     summary="Buscar citas por nombre",
     *     tags={"Citas"},
     *         name="name",
     *         in="path",
     *         required=true,
     *     ),
     *         response=200,
     *         description="Resultados de búsqueda por nombre"
     *     )
     * )
     */
    public function searchn($name): JsonResponse
    {
        try {
            if (empty($name) || strlen($name) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'El término de búsqueda debe tener al menos 2 caracteres'
                ], 400);
            }

            $citas = Citas2::where('Nombre', 'like', '%' . $name . '%')
                           ->orderBy('FechaProgramada', 'desc')
                           ->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Búsqueda completada',
                'data' => $citas->items(),
                'meta' => [
                    'current_page' => $citas->currentPage(),
                    'total' => $citas->total(),
                    'per_page' => $citas->perPage(),
                    'last_page' => $citas->lastPage(),
                    'search_term' => $name
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la búsqueda',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     *     path="/api/Citas/searcho/{order}",
     *     summary="Buscar citas por orden",
     *     tags={"Citas"},
     *         name="order",
     *         in="path",
     *         required=true,
     *     ),
     *         response=200,
     *         description="Resultados de búsqueda por orden"
     *     )
     * )
     */
    public function searcho($orden): JsonResponse
    {
        try {
            if (empty($orden)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El número de orden es requerido'
                ], 400);
            }

            $citas = Citas2::where('orden', 'like', '%' . $orden . '%')
                           ->orderBy('FechaProgramada', 'desc')
                           ->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Búsqueda completada',
                'data' => $citas->items(),
                'meta' => [
                    'current_page' => $citas->currentPage(),
                    'total' => $citas->total(),
                    'per_page' => $citas->perPage(),
                    'last_page' => $citas->lastPage(),
                    'search_term' => $orden
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la búsqueda',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
