<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\User; // Adicione esta linha
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // Inicialmente, não carrega nenhum log
        $logs = collect(); // Cria uma coleção vazia
        $users = User::all(); // Para o filtro de usuários
        $hasSearch = false; // Flag para verificar se há pesquisa

        // Verifica se algum filtro foi aplicado
        if ($request->filled('user_id') ||
            $request->filled('action') ||
            $request->filled('date_start') ||
            $request->filled('date_end') ||
            $request->filled('route') ||
            $request->filled('method') ||
            $request->filled('ip')) {

            $hasSearch = true; // Indica que há uma pesquisa
            $query = Log::query();

            // Aplicar os filtros
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->filled('action')) {
                $query->where('action', $request->action);
            }

            if ($request->filled('date_start')) {
                $query->whereDate('created_at', '>=', $request->date_start);
            }

//            if ($request->filled('date_end')) {
//                $query->whereDate('created_at', '<=', $request->date_end);
//            }

            if ($request->filled('route')) {
                $query->where('route', 'like', '%' . $request->route . '%');
            }

            if ($request->filled('method')) {
                $query->where('method',  $request->input('method'));

            }

            if ($request->filled('ip')) {
                $query->where('ip_address', 'like', '%' . $request->ip . '%');
            }

            // Executar a query apenas se houver filtros
            $logs = $query->latest()->get();
        }

        return view('admin.logs.index', compact('logs', 'users', 'hasSearch'));
    }
}
