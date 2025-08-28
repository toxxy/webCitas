<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="WebCitas API",
 *     version="1.0.0",
 *     description="API REST para gestión de citas médicas",
 *     @OA\Contact(
 *         email="admin@webcitas.com"
 *     )
 * )
 */

/**
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Servidor de desarrollo"
 * )
 */

/**
 * @OA\Schema(
 *     schema="Cita",
 *     type="object",
 *     title="Cita",
 *     description="Modelo de cita",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="orden", type="integer", example=12345),
 *     @OA\Property(property="descripcion", type="string", example="Consulta médica general"),
 *     @OA\Property(property="status", type="string", enum={"Sin-Programar", "Programada", "Realizada", "Cancelada"}, example="Programada"),
 *     @OA\Property(property="FechaProgramada", type="string", format="datetime", example="2024-08-27T10:00:00Z"),
 *     @OA\Property(property="FechaRealizada", type="string", format="datetime", nullable=true, example="2024-08-27T10:15:00Z"),
 *     @OA\Property(property="RFC", type="string", example="PEGJ850101ABC"),
 *     @OA\Property(property="Nombre", type="string", example="Juan Pérez García"),
 *     @OA\Property(property="created_at", type="string", format="datetime", example="2024-08-27T09:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="datetime", example="2024-08-27T09:30:00Z")
 * )
 */

/**
 * @OA\Schema(
 *     schema="CitaRequest",
 *     type="object",
 *     title="Solicitud de Cita",
 *     description="Datos requeridos para crear o actualizar una cita",
 *     required={"orden", "descripcion", "status", "FechaProgramada", "RFC", "Nombre"},
 *     @OA\Property(property="orden", type="integer", example=12345),
 *     @OA\Property(property="descripcion", type="string", maxLength=500, example="Consulta médica general"),
 *     @OA\Property(property="status", type="string", enum={"Sin-Programar", "Programada", "Realizada", "Cancelada"}, example="Programada"),
 *     @OA\Property(property="FechaProgramada", type="string", format="date", example="2024-08-27"),
 *     @OA\Property(property="FechaRealizada", type="string", format="date", nullable=true, example="2024-08-27"),
 *     @OA\Property(property="RFC", type="string", minLength=12, maxLength=13, example="PEGJ850101ABC"),
 *     @OA\Property(property="Nombre", type="string", maxLength=255, example="Juan Pérez García")
 * )
 */

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="Usuario",
 *     description="Modelo de usuario",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="Nombre", type="string", example="Juan"),
 *     @OA\Property(property="Apellidos", type="string", example="Pérez García"),
 *     @OA\Property(property="Email", type="string", format="email", example="juan@example.com"),
 *     @OA\Property(property="rfc", type="string", example="PEGJ850101ABC"),
 *     @OA\Property(property="created_at", type="string", format="datetime", example="2024-08-27T09:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="datetime", example="2024-08-27T09:30:00Z")
 * )
 */

/**
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Bearer token authentication using Laravel Sanctum"
 * )
 */

class SwaggerSchemas
{
    // Este archivo solo contiene las definiciones de schema para Swagger
}
