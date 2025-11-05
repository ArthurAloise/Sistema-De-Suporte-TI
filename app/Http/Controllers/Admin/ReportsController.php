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
use Illuminate\Support\Facades\Log as LogFacade;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
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

        if ($r->filled('status'))      $q->where('status', $r->status);
        if ($r->filled('priority'))    $q->where('prioridade', mb_strtolower($r->priority, 'UTF-8'));
        if ($r->filled('category_id')) $q->where('category_id', $r->category_id);
        if ($r->filled('type_id'))     $q->where('type_id', $r->type_id);
        if ($r->filled('usuario_id'))  $q->where('usuario_id', $r->usuario_id);
        if ($r->filled('tecnico_id'))  $q->where('tecnico_id', $r->tecnico_id);
        if ($r->filled('date_from'))   $q->whereDate('created_at', '>=', $r->date_from);
        if ($r->filled('date_to'))     $q->whereDate('created_at', '<=', $r->date_to);

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
        try {
            $qBase = $this->baseTicketQuery($r);

            $total      = (clone $qBase)->count();
            $abertos    = (clone $qBase)->whereIn('status', ['aberto', 'andamento', 'pendente'])->count();
            $resolvidos = (clone $qBase)->where('status', 'resolvido')->count();
            $overdue    = (clone $qBase)->whereIn('status', ['aberto', 'andamento'])
                ->whereNotNull('due_at')->where('due_at', '<', now())->count();

            // KPI novo: Abertos (Período)
            $openInPeriod = null;
            if ($r->filled('date_from') || $r->filled('date_to')) {
                $qOpen = Ticket::query()->whereIn('status', ['aberto', 'andamento', 'pendente']);
                if ($r->filled('status'))      $qOpen->where('status', $r->status);
                if ($r->filled('priority'))    $qOpen->where('prioridade', mb_strtolower($r->priority, 'UTF-8'));
                if ($r->filled('category_id')) $qOpen->where('category_id', $r->category_id);
                if ($r->filled('type_id'))     $qOpen->where('type_id', $r->type_id);
                if ($r->filled('date_from'))   $qOpen->whereDate('created_at', '>=', $r->date_from);
                if ($r->filled('date_to'))     $qOpen->whereDate('created_at', '<=', $r->date_to);
                $openInPeriod = $qOpen->count();
            }

            // SLA hit rate
            $slaHit = (clone $qBase)->whereNotNull('resolved_at')->whereNotNull('due_at')
                ->selectRaw("SUM(CASE WHEN resolved_at <= due_at THEN 1 ELSE 0 END) as hits, COUNT(*) as total")
                ->first();
            $slaRate = $slaHit && $slaHit->total ? round(($slaHit->hits / $slaHit->total) * 100, 2) : null;

            // MTTR (minutos) -> horas
            $mttrRow = (clone $qBase)->whereNotNull('resolved_at')
                ->selectRaw("AVG(TIMESTAMPDIFF(MINUTE, created_at, resolved_at)) as avg_min")
                ->first();
            $mttrHours = $mttrRow && $mttrRow->avg_min ? round($mttrRow->avg_min / 60, 2) : null;

            return response()->json(compact('total', 'abertos', 'openInPeriod', 'resolvidos', 'overdue', 'slaRate', 'mttrHours'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiTicketsByStatus(Request $r)
    {
        try {
            $rows = $this->baseTicketQuery($r)
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')->orderBy('status')->get();
            return response()->json($rows);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiTicketsByPriority(Request $r)
    {
        try {
            $rows = $this->baseTicketQuery($r)
                ->selectRaw('prioridade as label, COUNT(*) as total')
                ->groupBy('prioridade')->orderBy('prioridade')->get();
            return response()->json($rows);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiTicketsByCategory(Request $r)
    {
        try {
            $rows = $this->baseTicketQuery($r)
                ->join('categories', 'categories.id', '=', 'tickets.category_id')
                ->selectRaw('categories.nome as label, COUNT(*) as total')
                ->groupBy('categories.nome')->orderBy('categories.nome')->get();
            return response()->json($rows);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiTicketsByType(Request $r)
    {
        try {
            $rows = $this->baseTicketQuery($r)
                ->join('types', 'types.id', '=', 'tickets.type_id')
                ->selectRaw('types.nome as label, COUNT(*) as total')
                ->groupBy('types.nome')->orderBy('types.nome')->get();
            return response()->json($rows);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiTicketsCreatedDaily(Request $r)
    {
        try {
            $rows = $this->baseTicketQuery($r)
                ->selectRaw('DATE(created_at) as dia, COUNT(*) as total')
                ->groupBy(DB::raw('DATE(created_at)'))->orderBy('dia')->get();
            return response()->json($rows);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiTicketsResolvedDaily(Request $r)
    {
        try {
            $rows = $this->baseTicketQuery($r)
                ->whereNotNull('resolved_at')
                ->selectRaw('DATE(resolved_at) as dia, COUNT(*) as total')
                ->groupBy(DB::raw('DATE(resolved_at)'))->orderBy('dia')->get();
            return response()->json($rows);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiTicketsAging(Request $r)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiSlaHitRateMonthly(Request $r)
    {
        try {
            $rows = $this->baseTicketQuery($r)
                ->whereNotNull('resolved_at')->whereNotNull('due_at')
                ->selectRaw("
                    DATE_FORMAT(resolved_at, '%Y-%m') as mes,
                    SUM(CASE WHEN resolved_at <= due_at THEN 1 ELSE 0 END) as dentro,
                    COUNT(*) as total
                ")
                ->groupBy('mes')->orderBy('mes')->get();

            $out = $rows->map(fn($x) => [
                'mes' => $x->mes,
                'sla' => $x->total ? round(($x->dentro / $x->total) * 100, 2) : null
            ]);

            return response()->json($out);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /* ===== NOVOS endpoints: Ranking & SLA ===== */

    public function apiTopTypes(Request $r)
    {
        try {
            $q = $this->baseTicketQuery($r)
                ->join('types', 'types.id', '=', 'tickets.type_id')
                ->selectRaw('types.nome as label, COUNT(*) as total')
                ->groupBy('types.id', 'types.nome')
                ->orderByDesc('total')
                ->limit(10);
            return response()->json($q->get());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiTopCategories(Request $r)
    {
        try {
            $q = $this->baseTicketQuery($r)
                ->join('categories', 'categories.id', '=', 'tickets.category_id')
                ->selectRaw('categories.nome as label, COUNT(*) as total')
                ->groupBy('categories.id', 'categories.nome')
                ->orderByDesc('total')
                ->limit(10);
            return response()->json($q->get());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiTopTechnicians(Request $r)
    {
        try {
            $q = $this->baseTicketQuery($r)
                ->leftJoin('users as tech', 'tech.id', '=', 'tickets.tecnico_id')
                ->selectRaw("COALESCE(tech.name,'Sem técnico') as tecnico, COUNT(*) as total")
                ->groupBy('tech.id', 'tech.name')
                ->orderByDesc('total')
                ->limit(10);
            return response()->json($q->get());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiOnTimeOverdue(Request $r)
    {
        try {
            $q = $this->baseTicketQuery($r);

            $onTime = (clone $q)->whereNotNull('resolved_at')
                ->whereNotNull('due_at')
                ->whereColumn('resolved_at', '<=', 'due_at')
                ->count();

            $overdueResolved = (clone $q)->whereNotNull('resolved_at')
                ->whereNotNull('due_at')
                ->whereColumn('resolved_at', '>', 'due_at')
                ->count();

            $overdueOpen = (clone $q)->whereNull('resolved_at')
                ->whereNotNull('due_at')
                ->where('due_at', '<', now())
                ->count();

            return response()->json([
                'on_time' => $onTime,
                'overdue' => $overdueResolved + $overdueOpen,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiTopPriorities(Request $r)
    {
        try {
            $q = $this->baseTicketQuery($r)
                ->selectRaw('prioridade as label, COUNT(*) as total')
                ->groupBy('prioridade')
                ->orderByDesc('total');
            return response()->json($q->get());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiTechniciansIdle(Request $r)
    {
        try {
            // Período alvo
            $from = $r->date_from ?? now()->subDays(6)->toDateString();
            $to   = $r->date_to   ?? now()->toDateString();

            // VERSÃO SIMPLIFICADA: Pega todos os usuários que já foram técnicos alguma vez
            $allTechs = DB::table('tickets')
                ->whereNotNull('tecnico_id')
                ->distinct()
                ->pluck('tecnico_id');

            // Se não houver técnicos, retorna vazio
            if ($allTechs->isEmpty()) {
                return response()->json([]);
            }

            // Busca técnicos que NÃO tiveram tickets no período
            $activeTechsInPeriod = DB::table('tickets')
                ->whereNotNull('tecnico_id')
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->distinct()
                ->pluck('tecnico_id');

            // Técnicos ociosos = todos - ativos
            $idleTechIds = $allTechs->diff($activeTechsInPeriod);

            if ($idleTechIds->isEmpty()) {
                return response()->json([]);
            }

            // Busca informações dos técnicos ociosos
            $rows = DB::table('users as u')
                ->leftJoin('tickets as t', 't.tecnico_id', '=', 'u.id')
                ->whereIn('u.id', $idleTechIds)
                ->selectRaw('u.name as tecnico, DATEDIFF(NOW(), MAX(t.created_at)) as diasSemAtividade')
                ->groupBy('u.id', 'u.name')
                ->orderBy('u.name')
                ->get();

            return response()->json($rows);
        } catch (\Exception $e) {
            LogFacade::error('Erro em apiTechniciansIdle: ' . $e->getMessage());
            return response()->json([], 200); // Retorna array vazio em caso de erro
        }
    }

    /* ========
     * Logs API
     * ======== */

    public function apiLogsTopActions(Request $r)
    {
        try {
            $rows = $this->baseLogQuery($r)
                ->selectRaw('action, COUNT(*) as total')
                ->groupBy('action')
                ->orderByDesc('total')
                ->limit(15)
                ->get();
            return response()->json($rows);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiLogsByDay(Request $r)
    {
        try {
            $rows = $this->baseLogQuery($r)
                ->selectRaw('DATE(created_at) as dia, COUNT(*) as total')
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('dia')
                ->get();
            return response()->json($rows);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiLogsTopUsers(Request $r)
    {
        try {
            $rows = $this->baseLogQuery($r)
                ->join('users', 'users.id', '=', 'logs.user_id')
                ->selectRaw('users.name as usuario, COUNT(*) as total')
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('total')
                ->limit(15)
                ->get();
            return response()->json($rows);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiLogsTopRoutes(Request $r)
    {
        try {
            $rows = $this->baseLogQuery($r)
                ->selectRaw('route, COUNT(*) as total')
                ->groupBy('route')
                ->orderByDesc('total')
                ->limit(15)
                ->get();
            return response()->json($rows);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function apiLogsMethods(Request $r)
    {
        try {
            $rows = $this->baseLogQuery($r)
                ->selectRaw('method as label, COUNT(*) as total')
                ->groupBy('method')
                ->orderByDesc('total')
                ->get();
            return response()->json($rows);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
