<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Type;
use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Cache;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // opções de filtro para selects (sem lógica pesada na Blade)
        $categories = Category::orderBy('nome')->get(['id', 'nome']);
        $types      = Type::orderBy('nome')->get(['id', 'nome']);
        $users      = User::orderBy('name')->get(['id', 'name']);

        return view('admin.reports.index', compact('categories', 'types', 'users'));
    }

    /* =======================
     * Helpers de filtros base
     * ======================= */
    protected function baseTicketQuery(Request $r)
    {
        $q = Ticket::query();

        if ($r->filled('status'))     $q->where('status', $r->status);
        if ($r->filled('priority'))   $q->where('prioridade', mb_strtolower($r->priority, 'UTF-8'));
        if ($r->filled('category_id')) $q->where('category_id', $r->category_id);
        if ($r->filled('type_id'))    $q->where('type_id', $r->type_id);
        if ($r->filled('usuario_id')) $q->where('usuario_id', $r->usuario_id);
        if ($r->filled('tecnico_id')) $q->where('tecnico_id', $r->tecnico_id);
        if ($r->filled('date_from'))  $q->whereDate('created_at', '>=', $r->date_from);
        if ($r->filled('date_to'))    $q->whereDate('created_at', '<=', $r->date_to);

        return $q;
    }

    protected function baseLogQuery(Request $r)
    {
        $q = Log::query();
        if ($r->filled('user_id'))    $q->where('user_id', $r->user_id);
        if ($r->filled('action'))     $q->where('action', $r->action);
        if ($r->filled('route'))      $q->where('route', 'like', '%' . $r->route . '%');
        if ($r->filled('method'))     $q->where('method', $r->method);
        if ($r->filled('ip'))         $q->where('ip_address', 'like', '%' . $r->ip . '%');
        if ($r->filled('date_from'))  $q->whereDate('created_at', '>=', $r->date_from);
        if ($r->filled('date_to'))    $q->whereDate('created_at', '<=', $r->date_to);
        return $q;
    }

    /* ===========
     * Tickets API
     * =========== */

    public function apiTicketsKpis(Request $r)
    {
        $key = 'tickets_kpis_' . md5(json_encode($r->all()));
        return Cache::remember($key, 300, function () use ($r) {
            $qBase = $this->baseTicketQuery($r);

            $total           = (clone $qBase)->count();
            $abertos         = (clone $qBase)->whereIn('status', ['aberto', 'andamento', 'pendente'])->count();
            $resolvidos      = (clone $qBase)->where('status', 'resolvido')->count();
            $overdue         = (clone $qBase)->whereIn('status', ['aberto', 'andamento'])
                ->whereNotNull('due_at')->where('due_at', '<', now())->count();

            // SLA hit rate (tickets resolvidos com resolved_at <= due_at)
            $slaHit = (clone $qBase)->whereNotNull('resolved_at')->whereNotNull('due_at')
                ->selectRaw("SUM(CASE WHEN resolved_at <= due_at THEN 1 ELSE 0 END) as hits, COUNT(*) as total")
                ->first();
            $slaRate = $slaHit && $slaHit->total ? round(($slaHit->hits / $slaHit->total) * 100, 2) : null;

            // MTTR (minutos) -> horas
            $mttrRow = (clone $qBase)->whereNotNull('resolved_at')
                ->selectRaw("AVG(TIMESTAMPDIFF(MINUTE, created_at, resolved_at)) as avg_min")
                ->first();
            $mttrHours = $mttrRow && $mttrRow->avg_min ? round($mttrRow->avg_min / 60, 2) : null;

            return response()->json(compact('total', 'abertos', 'resolvidos', 'overdue', 'slaRate', 'mttrHours'));
        });
    }

    public function apiTicketsByStatus(Request $r)
    {
        $rows = $this->baseTicketQuery($r)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')->orderBy('status')->get();
        return response()->json($rows);
    }

    public function apiTicketsByPriority(Request $r)
    {
        $rows = $this->baseTicketQuery($r)
            ->selectRaw('prioridade as label, COUNT(*) as total')
            ->groupBy('prioridade')->orderBy('prioridade')->get();
        return response()->json($rows);
    }

    public function apiTicketsByCategory(Request $r)
    {
        $rows = $this->baseTicketQuery($r)
            ->join('categories', 'categories.id', '=', 'tickets.category_id')
            ->selectRaw('categories.nome as label, COUNT(*) as total')
            ->groupBy('categories.nome')->orderBy('categories.nome')->get();
        return response()->json($rows);
    }

    public function apiTicketsByType(Request $r)
    {
        $rows = $this->baseTicketQuery($r)
            ->join('types', 'types.id', '=', 'tickets.type_id')
            ->selectRaw('types.nome as label, COUNT(*) as total')
            ->groupBy('types.nome')->orderBy('types.nome')->get();
        return response()->json($rows);
    }

    public function apiTicketsCreatedDaily(Request $r)
    {
        $rows = $this->baseTicketQuery($r)
            ->selectRaw('DATE(created_at) as dia, COUNT(*) as total')
            ->groupBy(DB::raw('DATE(created_at)'))->orderBy('dia')->get();
        return response()->json($rows);
    }

    public function apiTicketsResolvedDaily(Request $r)
    {
        $rows = $this->baseTicketQuery($r)
            ->whereNotNull('resolved_at')
            ->selectRaw('DATE(resolved_at) as dia, COUNT(*) as total')
            ->groupBy(DB::raw('DATE(resolved_at)'))->orderBy('dia')->get();
        return response()->json($rows);
    }

    public function apiTicketsAging(Request $r)
    {
        // apenas abertos/andamento/pendente
        $q = $this->baseTicketQuery($r)->whereIn('status', ['aberto', 'andamento', 'pendente']);

        $row = $q->selectRaw("
            SUM(CASE WHEN DATEDIFF(NOW(), created_at) < 1 THEN 1 ELSE 0 END) AS d0_1,
            SUM(CASE WHEN DATEDIFF(NOW(), created_at) BETWEEN 1 AND 3 THEN 1 ELSE 0 END) AS d1_3,
            SUM(CASE WHEN DATEDIFF(NOW(), created_at) BETWEEN 4 AND 7 THEN 1 ELSE 0 END) AS d4_7,
            SUM(CASE WHEN DATEDIFF(NOW(), created_at) BETWEEN 8 AND 14 THEN 1 ELSE 0 END) AS d8_14,
            SUM(CASE WHEN DATEDIFF(NOW(), created_at) BETWEEN 15 AND 30 THEN 1 ELSE 0 END) AS d15_30,
            SUM(CASE WHEN DATEDIFF(NOW(), created_at) > 30 THEN 1 ELSE 0 END) AS d30p
        ")->first();

        $data = [
            ['label' => '0–1d',   'total' => (int) ($row->d0_1 ?? 0)],
            ['label' => '1–3d',   'total' => (int) ($row->d1_3 ?? 0)],
            ['label' => '4–7d',   'total' => (int) ($row->d4_7 ?? 0)],
            ['label' => '8–14d',  'total' => (int) ($row->d8_14 ?? 0)],
            ['label' => '15–30d', 'total' => (int) ($row->d15_30 ?? 0)],
            ['label' => '30+d',   'total' => (int) ($row->d30p ?? 0)],
        ];

        return response()->json($data);
    }

    public function apiSlaHitRateMonthly(Request $r)
    {
        $rows = $this->baseTicketQuery($r)
            ->whereNotNull('resolved_at')->whereNotNull('due_at')
            ->selectRaw("
                DATE_FORMAT(resolved_at, '%Y-%m') as mes,
                SUM(CASE WHEN resolved_at <= due_at THEN 1 ELSE 0 END) as dentro,
                COUNT(*) as total
            ")
            ->groupBy('mes')->orderBy('mes')->get();

        // retorna % por mês
        $out = $rows->map(fn($x) => [
            'mes'   => $x->mes,
            'sla'   => $x->total ? round(($x->dentro / $x->total) * 100, 2) : null
        ]);

        return response()->json($out);
    }

    /* ========
     * Logs API
     * ======== */

    public function apiLogsTopActions(Request $r)
    {
        $rows = $this->baseLogQuery($r)
            ->selectRaw('action, COUNT(*) as total')
            ->groupBy('action')->orderByDesc('total')->limit(15)->get();
        return response()->json($rows);
    }

    public function apiLogsByDay(Request $r)
    {
        $rows = $this->baseLogQuery($r)
            ->selectRaw('DATE(created_at) as dia, COUNT(*) as total')
            ->groupBy(DB::raw('DATE(created_at)'))->orderBy('dia')->get();
        return response()->json($rows);
    }

    public function apiLogsTopUsers(Request $r)
    {
        $rows = $this->baseLogQuery($r)
            ->join('users', 'users.id', '=', 'logs.user_id')
            ->selectRaw('users.name as usuario, COUNT(*) as total')
            ->groupBy('users.name')->orderByDesc('total')->limit(15)->get();
        return response()->json($rows);
    }

    public function apiLogsTopRoutes(Request $r)
    {
        $rows = $this->baseLogQuery($r)
            ->selectRaw('route, COUNT(*) as total')
            ->groupBy('route')->orderByDesc('total')->limit(15)->get();
        return response()->json($rows);
    }

    public function apiLogsMethods(Request $r)
    {
        $rows = $this->baseLogQuery($r)
            ->selectRaw('method as label, COUNT(*) as total')
            ->groupBy('method')->orderByDesc('total')->get();
        return response()->json($rows);
    }

    /* ===========
     * Export CSVs
     * =========== */

    public function exportTicketsCsv(Request $r): StreamedResponse
    {
        $filename = 'tickets_export_' . now()->format('Ymd_His') . '.csv';
        $q = $this->baseTicketQuery($r)->with(['category', 'type', 'usuario', 'tecnico']);

        return response()->streamDownload(function () use ($q) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Título', 'Status', 'Prioridade', 'Categoria', 'Tipo', 'Usuário', 'Técnico', 'Criado em', 'Due at', 'Resolvido em']);
            $q->orderBy('created_at')->chunk(1000, function ($chunk) use ($out) {
                foreach ($chunk as $t) {
                    fputcsv($out, [
                        $t->id,
                        $t->titulo,
                        $t->status,
                        $t->prioridade,
                        optional($t->category)->nome,
                        optional($t->type)->nome,
                        optional($t->usuario)->name,
                        optional($t->tecnico)->name,
                        optional($t->created_at)->toDateTimeString(),
                        optional($t->due_at)->toDateTimeString(),
                        optional($t->resolved_at)->toDateTimeString(),
                    ]);
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function exportLogsCsv(Request $r): StreamedResponse
    {
        $filename = 'logs_export_' . now()->format('Ymd_His') . '.csv';
        $q = $this->baseLogQuery($r)->with('user');

        return response()->streamDownload(function () use ($q) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Data', 'Usuário', 'Ação', 'Rota', 'Method', 'IP']);
            $q->orderBy('created_at')->chunk(1000, function ($chunk) use ($out) {
                foreach ($chunk as $log) {
                    fputcsv($out, [
                        $log->id,
                        optional($log->created_at)->toDateTimeString(),
                        optional($log->user)->name,
                        $log->action,
                        $log->route,
                        $log->method,
                        $log->ip_address,
                    ]);
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
