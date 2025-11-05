<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('name')->get(); // Para o dropdown de filtro
        $query = Log::query()->with('user'); // Inicia a query com eager loading

        $hasSearch = !empty(array_filter($request->except('page'))); // Verifica se há filtros além da página

        // Aplicar os filtros SE existirem
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('date_start')) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) { // Descomentei e corrigi
            $query->whereDate('created_at', '<=', $request->date_end);
        }
        if ($request->filled('route')) {
            $query->where('route', 'like', '%' . $request->route . '%');
        }
        if ($request->filled('method')) {
            $query->where('method', $request->input('method'));
        }
        if ($request->filled('ip')) {
            $query->where('ip_address', 'like', '%' . $request->ip . '%');
        }

        // *** ALTERAÇÃO PRINCIPAL: Paginar SEMPRE ***
        $perPage = $request->input('per_page', 15); // Pega 'per_page' da request ou usa 15
        $logs = $query->latest()->paginate($perPage)->withQueryString(); // Usa paginate() e mantém filtros

        return view('admin.logs.index', compact('logs', 'users', 'hasSearch'));
    }
}
