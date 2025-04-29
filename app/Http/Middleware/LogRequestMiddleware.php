<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class LogRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected $sensitivePaths = [
        'password',
        'token',
        'secret',
        '_token',
        'current_password',
        'new_password'
    ];


    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Não registrar logs para alguns endpoints específicos
        if ($this->shouldSkipLogging($request)) {
            return $response;
        }

        // Identificar a ação baseada no método HTTP
        $action = $this->determineAction($request);

        // Obter informações do controller e action
        $routeInfo = $this->getRouteInfo();

        // Capturar dados relevantes da requisição
        $requestData = $this->sanitizeRequestData($request->all());

        // Criar o log
        Log::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'route' => $request->path(),
            'method' => $request->method(),
            'controller' => $routeInfo['controller'],
            'action_name' => $routeInfo['action'],
            'model' => $this->getAffectedModel($request),
            'record_id' => $this->getRecordId($request),
            'description' => $this->generateDescription($request, $action, $routeInfo),
            'request_data' => $requestData ? json_encode($requestData) : null,
            'old_values' => $this->getOldValues($request),
            'new_values' => $this->getNewValues($request),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return $response;
    }

    protected function shouldSkipLogging(Request $request)
    {
        $skipPaths = ['_debugbar', 'livewire', 'logs', 'sanctum'];
        return collect($skipPaths)->contains(function ($path) use ($request) {
            return str_starts_with($request->path(), $path);
        });
    }

    protected function determineAction(Request $request)
    {
        $method = $request->method();
        $path = $request->path();

        if (str_contains($path, 'login')) return 'LOGIN';
        if (str_contains($path, 'logout')) return 'LOGOUT';

        switch ($method) {
            case 'POST': return 'CREATE';
            case 'PUT':
            case 'PATCH': return 'UPDATE';
            case 'DELETE': return 'DELETE';
            default: return 'ACCESS';
        }
    }

    protected function getRouteInfo()
    {
        $route = Route::current();
        if (!$route) return ['controller' => 'Unknown', 'action' => 'Unknown'];

        $action = $route->getActionName();

        // Extrair nome do controller e método
        if (str_contains($action, '@')) {
            list($controller, $method) = explode('@', $action);
            return [
                'controller' => class_basename($controller),
                'action' => $method
            ];
        }

        return [
            'controller' => class_basename($action),
            'action' => '__invoke'
        ];
    }

    protected function sanitizeRequestData(array $data)
    {
        return collect($data)
            ->except($this->sensitivePaths)
            ->filter(function ($value) {
                return !is_null($value) && $value !== '';
            })
            ->toArray();
    }

    protected function getAffectedModel(Request $request)
    {
        $route = Route::current();
        if (!$route) return null;

        $parameters = $route->parameters();
        foreach ($parameters as $parameter) {
            if (is_object($parameter)) {
                return get_class($parameter);
            }
        }

        return null;
    }

    protected function getRecordId(Request $request)
    {
        $route = Route::current();
        if (!$route) return null;

        $parameters = $route->parameters();
        foreach ($parameters as $parameter) {
            if (is_object($parameter) && method_exists($parameter, 'getKey')) {
                return $parameter->getKey();
            }
        }

        return null;
    }

    protected function generateDescription(Request $request, string $action, array $routeInfo)
    {
        $user = Auth::user();
        $userName = $user ? $user->name : 'Sistema';
        $path = $request->path();

        switch ($action) {
            case 'LOGIN':
                return "Usuário {$userName} realizou login no sistema";
            case 'LOGOUT':
                return "Usuário {$userName} realizou logout do sistema";
            case 'CREATE':
                return "Usuário {$userName} criou novo registro em {$path}";
            case 'UPDATE':
                return "Usuário {$userName} atualizou registro em {$path}";
            case 'DELETE':
                return "Usuário {$userName} removeu registro em {$path}";
            default:
                return "Usuário {$userName} acessou {$routeInfo['controller']}@{$routeInfo['action']}";
        }
    }

    protected function getOldValues(Request $request)
    {
        $route = Route::current();
        if (!$route) return null;

        $parameters = $route->parameters();
        foreach ($parameters as $parameter) {
            if (is_object($parameter) && method_exists($parameter, 'getOriginal')) {
                return json_encode($parameter->getOriginal());
            }
        }

        return null;
    }

    protected function getNewValues(Request $request)
    {
        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH')) {
            return json_encode($this->sanitizeRequestData($request->all()));
        }

        return null;
    }
}
