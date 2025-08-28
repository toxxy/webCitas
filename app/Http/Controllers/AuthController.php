<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    /**
     *     path="/api/register",
     *     summary="Registrar nuevo usuario",
     *     tags={"Autenticación"},
     *         required=true,
     *         )
     *     ),
     *         response=201,
     *         description="Usuario registrado exitosamente"
     *     ),
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'Nombre' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s]+$/'],
                'Apellidos' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s]+$/'],
                'Email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,Email'],
                'Password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'],
                'rfc' => ['required', 'string', 'min:12', 'max:13', 'confirmed', 'regex:/^[A-Z]{4}[0-9]{6}[A-Z0-9]{3}$/', 'unique:users,rfc'],
            ], [
                'Password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un carácter especial.',
                'Nombre.regex' => 'El nombre solo puede contener letras y espacios.',
                'Apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',
                'rfc.regex' => 'El RFC debe tener un formato válido (LLLL######LLL).',
                'Email.email' => 'Debe proporcionar un email válido.'
            ]);

            $user = User::create([
                'Nombre' => $validated['Nombre'],
                'Apellidos' => $validated['Apellidos'],
                'Email' => $validated['Email'],
                'Password' => bcrypt($validated['Password']),
                'rfc' => strtoupper($validated['rfc']),
            ]);

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'Nombre' => $user->Nombre,
                        'Apellidos' => $user->Apellidos,
                        'Email' => $user->Email,
                        'rfc' => $user->rfc
                    ],
                    'token' => $token
                ]
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
                'message' => 'Error al registrar usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     *     path="/api/login",
     *     summary="Iniciar sesión",
     *     tags={"Autenticación"},
     *         required=true,
     *         )
     *     ),
     *         response=200,
     *         description="Login exitoso"
     *     ),
     *         response=401,
     *         description="Credenciales incorrectas"
     *     ),
     *         response=429,
     *         description="Demasiados intentos de login"
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        // Rate limiting para prevenir ataques de fuerza bruta
        $key = 'login.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Demasiados intentos de login. Intente nuevamente en {$seconds} segundos."
            ], 429);
        }

        try {
            $validated = $request->validate([
                'Email' => 'required|string|email',
                'Password' => 'required|string|min:8',
            ]);

            $user = User::where('Email', $validated['Email'])->first();

            if (!$user || !Hash::check($validated['Password'], $user->Password)) {
                RateLimiter::hit($key, 300); // Bloqueo por 5 minutos
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }

            // Limpiar rate limiting en login exitoso
            RateLimiter::clear($key);

            // Revocar tokens anteriores por seguridad
            $user->tokens()->delete();

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'Nombre' => $user->Nombre,
                        'Apellidos' => $user->Apellidos,
                        'Email' => $user->Email,
                        'rfc' => $user->rfc
                    ],
                    'token' => $token
                ]
            ], 200);

        } catch (ValidationException $e) {
            RateLimiter::hit($key, 300);
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     *     path="/api/logout",
     *     summary="Cerrar sesión",
     *     tags={"Autenticación"},
     *     security={{"sanctum":{}}},
     *         response=200,
     *         description="Logout exitoso"
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            if (auth()->check()) {
                // Revocar solo el token actual
                $request->user()->currentAccessToken()->delete();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Sesión cerrada exitosamente'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     *     path="/api/UInfo",
     *     summary="Obtener información del usuario autenticado",
     *     tags={"Autenticación"},
     *     security={{"sanctum":{}}},
     *         response=200,
     *         description="Información del usuario"
     *     )
     * )
     */
    public function UInfo(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'message' => 'Información del usuario obtenida exitosamente',
                'data' => [
                    'id' => $user->id,
                    'Nombre' => $user->Nombre,
                    'Apellidos' => $user->Apellidos,
                    'Email' => $user->Email,
                    'rfc' => $user->rfc,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información del usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
