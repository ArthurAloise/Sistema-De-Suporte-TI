<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Type;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userCount        = User::count();
        $openTicketsCount = Ticket::whereIn('status', ['aberto','andamento'])->count();

        // KPIs de SLA
        $slaOverdueCount = Ticket::whereIn('status', ['aberto','andamento'])
            ->whereNotNull('due_at')->where('due_at', '<', now())->count();

        $slaDue24hCount = Ticket::whereIn('status', ['aberto','andamento'])
            ->whereNotNull('due_at')
            ->whereBetween('due_at', [now(), now()->addDay()])
            ->count();

        // Filtros
        $perPage  = (int)($request->get('per_page', 5));
        $status   = $request->get('status');
        $priority = $request->get('priority') ? mb_strtolower($request->get('priority'), 'UTF-8') : null; // 'baixa','media','alta','muito alta'
        $typeId   = $request->get('type_id');
        $catId    = $request->get('category_id');
        $ticketId = $request->get('ticket_id');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');
        $sla      = $request->get('sla'); // 'overdue' | 'due24h'

        $query = Ticket::with(['usuario','category','type']);

        if ($ticketId) $query->where('id', $ticketId);
        if ($status)   $query->where('status', $status);
        if ($priority) $query->where('prioridade', $priority);
        if ($typeId)   $query->where('type_id', $typeId);
        if ($catId)    $query->where('category_id', $catId);

//        if ($dateFrom) $query->whereDate('created_at', '>=', $dateFrom);
//        if ($dateTo)   $query->whereDate('created_at', '<=', $dateTo);

        if ($sla === 'overdue') {
            $query->whereIn('status', ['aberto','andamento'])
                ->whereNotNull('due_at')->where('due_at','<', now());
        } elseif ($sla === 'due24h') {
            $query->whereIn('status', ['aberto','andamento'])
                ->whereNotNull('due_at')
                ->whereBetween('due_at', [now(), now()->addDay()]);
        }

        // Ordenação: priorize quem vence antes
        $tickets = $query
            ->orderByRaw('CASE WHEN due_at IS NULL THEN 1 ELSE 0 END ASC') // due_at primeiro
            ->orderBy('due_at', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

//        $types      = Type::orderBy('nome')->get();
//        $categories = Category::orderBy('nome')->get();

        return view('admin.dashboard', compact(
            'userCount',
            'openTicketsCount',
            'slaOverdueCount',
            'slaDue24hCount',
            'tickets',
//            'types',
//            'categories',
            'perPage',
            'status',
            'priority',
            'typeId',
            'catId',
            'ticketId',
//            'dateFrom',
            'dateTo',
            'sla'
        ));
    }
}
