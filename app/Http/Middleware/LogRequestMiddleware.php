<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Defina os valores que você quer registrar no log
        $response = $next($request);

        // Capture as informações para o log
        app('log')->info('Ação registrada: ' . $request->method() . ' ' . $request->path());

        Log::create([
            'user_id' => Auth::id(),
            'action' => 'ACCESS',
            'route' => $request->path(),
            'method' => $request->method(),
            'controller' => class_basename(get_class($this)), // Controller atual
            'model' => null, // Caso tenha um modelo afetado
            'record_id' => null, // ID do registro afetado
            'old_values' => null,
            'new_values' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return $response;
    }
}
