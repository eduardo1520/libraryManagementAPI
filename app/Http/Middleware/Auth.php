<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $token = str_replace('Bearer ', '', $request->header('Authorization'));
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            // Verificações adicionais
            if ($decoded->exp < time()) {
                return response()->json(['error' => 'Token expirado'], 401);
            }

            if ($decoded->iss !== env('JWT_ISSUER')) {
                return response()->json(['error' => 'Emissor inválido'], 401);
            }

            return $next($request);
        } catch (ExpiredException $e) {
            return response()->json(['error' => 'Token expirado'], 401);
        } catch (Exception $e) {
            // Registre a exceção em um log
            Log::error('Erro ao decodificar token: ' . $e->getMessage());
            return response()->json(['error' => 'Token inválido'], 401);
        }
    }
}
