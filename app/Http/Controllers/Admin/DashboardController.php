<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userCount = User::count();
        $openTicketsCount = Ticket::whereIn('status', ['aberto', 'andamento'])->count();

        // Iniciar a query
        $query = Ticket::query();

        // Pesquisa por ID do chamado
        if ($request->filled('ticket_id')) {
            $query->where('id', $request->ticket_id);
        }

        // Pesquisa por data
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Ordenar por data de criação (mais recentes primeiro)
        $tickets = $query->orderBy('created_at', 'desc')->paginate(5);

        return view('admin.dashboard', compact(
            'userCount',
            'openTicketsCount',
            'tickets'
        ));
    }

}
