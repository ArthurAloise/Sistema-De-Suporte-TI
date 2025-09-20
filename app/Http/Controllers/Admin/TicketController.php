<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketHistory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;
use App\Services\SlaService;
use Illuminate\Support\Str;
use App\Mail\ChamadoCriadoMail;
use App\Mail\ChamadoResolvidoMail;
use App\Mail\TecnicoAlteradoMail;
use App\Mail\ChamadoPendenteMail;
use Illuminate\Support\Facades\Mail;



class TicketController extends Controller
{
    public function index()
    {
        //        $tickets = Ticket::with(['usuario', 'tecnico', 'category', 'type'])->get();

        //        // Aqui estamos pegando os chamados que o usuário abriu e os que foram atribuídos a ele.
        //        $tickets = Ticket::with(['usuario', 'tecnico', 'category', 'type'])
        //        ->where('usuario_id', Auth::id()) // Chamados abertos pelo usuário
        //        ->orWhere('tecnico_id', Auth::id()) // Chamados atribuídos ao usuário
        //        ->latest() // Ordena os chamados mais recentes
        //        ->get();

        // Pegando os chamados que o usuário abriu e os chamados atribuídos a ele
        $tickets_abertos = Ticket::with(['usuario', 'tecnico', 'category', 'type'])
            ->where('usuario_id', Auth::id()) // Chamados abertos pelo usuário
            ->latest() // Ordena os chamados mais recentes
            ->paginate(10, ['*'], 'abertos_page');

        $tickets_atribuido = Ticket::with(['usuario', 'tecnico', 'category', 'type'])
            ->where('tecnico_id', Auth::id()) // Chamados atribuídos ao usuário
            ->latest() // Ordena os chamados mais recentes
            ->paginate(10, ['*'], 'atribuido_page');

        return view('tickets.index', compact('tickets_abertos', 'tickets_atribuido'));
    }

    public function create()
    {
        $categories = Category::all();
        $types = Type::all();
        return view('tickets.create', compact('categories', 'types'));
    }

    // store()
    public function store(Request $request)
    {
        $request->validate([
            'titulo'      => 'required|string|max:255',
            'descricao'   => 'required',
            'category_id' => 'required|exists:categories,id',
            'type_id'     => 'required|exists:types,id',
        ]);

        $type     = Type::findOrFail($request->type_id);
        $category = Category::findOrFail($request->category_id);

        ['priority' => $prioridade, 'hours' => $horas] = SlaService::resolve($type->nome, $category->nome);

        $now   = now();
        $dueAt = SlaService::dueAt($prioridade, $now, null);

        $chamado = Ticket::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'prioridade' => $prioridade,
            'category_id' => $request->category_id,
            'type_id' => $request->type_id,
            'usuario_id' => Auth::id(),
            'due_at'      => $dueAt,
        ]);

        // 🔔 Enviar e-mail para o criador do chamado
        Mail::to(Auth::user()->email)->send(new ChamadoCriadoMail($chamado));

        return redirect()->route('user.dashboard')->with('success', 'Ticket criado com sucesso!');
    }


    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $categories = Category::all();
        $types = Type::all();
        return view('tickets.edit', compact('ticket', 'categories', 'types'));
    }

    //    public function update(Request $request, $id){
    //        $request->validate([
    //            'titulo'      => 'required|string|max:255',
    //            'descricao'   => 'required',
    //            'category_id' => 'required|exists:categories,id',
    //            'type_id'     => 'required|exists:types,id',
    //        ]);
    //
    //        $ticket = Ticket::findOrFail($id);
    //
    //        if ($request->hasAny(['type_id','category_id'])) {
    //            $type     = Type::find($request->type_id ?? $ticket->type_id);
    //            $category = Category::find($request->category_id ?? $ticket->category_id);
    //
    //            ['priority' => $prioridade, 'hours' => $hours] = SlaService::resolve($type->nome ?? null, $category->nome ?? null);
    //
    //            $ticket->prioridade = $prioridade;
    //
    //            // define novo due_at relativo “agora” (ou à data original se preferir outra política)
    //            $ticket->due_at = SlaService::dueAt($prioridade, now(), null);
    //        }
    //
    //        $ticket->update([
    //            'titulo' => $request->titulo,
    //            'descricao' => $request->descricao,
    //            'category_id' => $request->category_id,
    //            'type_id' => $request->type_id,
    //        ]);
    //
    //        return redirect()->route('tickets.index')->with('success', 'Ticket atualizado com sucesso!');
    //    }

    public function update(Request $request, $id)
    {
        // erros isolados no modal "editTicket"
        $validated = $request->validateWithBag('editTicket', [
            'titulo'      => 'required|string|max:255',
            'descricao'   => 'required',
            'category_id' => 'required|exists:categories,id',
            'type_id'     => 'required|exists:types,id',
        ]);

        $ticket = Ticket::findOrFail($id);

        // Snapshot do "antes" para histórico
        $before = [
            'titulo'      => $ticket->titulo,
            'descricao'   => $ticket->descricao,
            'category_id' => $ticket->category_id,
            'type_id'     => $ticket->type_id,
        ];

        // Recalcular prioridade + SLA se Type/Category mudar
        $typeChanged = (int)$validated['type_id'] !== (int)$ticket->type_id;
        $catChanged  = (int)$validated['category_id'] !== (int)$ticket->category_id;

        if ($typeChanged || $catChanged) {
            $type     = Type::find($validated['type_id']);
            $category = Category::find($validated['category_id']);
            ['priority' => $prioridade] = SlaService::resolve($type->nome ?? null, $category->nome ?? null);
            $ticket->prioridade = $prioridade;
            $ticket->due_at     = SlaService::dueAt($prioridade, now(), null);
        }

        // Atualiza campos básicos
        $ticket->titulo      = $validated['titulo'];
        $ticket->descricao   = $validated['descricao'];
        $ticket->category_id = $validated['category_id'];
        $ticket->type_id     = $validated['type_id'];
        $ticket->save();

        // Histórico de edição (apenas diffs)
        $changes = [];
        if ($before['titulo']      !== $ticket->titulo)      $changes[] = "Título: '{$before['titulo']}' → '{$ticket->titulo}'";
        if ($before['descricao'] !== $ticket->descricao) {
            $max = 1000; // ajuste conforme desejar
            $oldDesc = Str::limit($before['descricao'] ?? '', $max);
            $newDesc = Str::limit($ticket->descricao ?? '', $max);

            $changes[] = "Descrição alterada:\n[ANTES]\n{$oldDesc}\n\n[DEPOIS]\n{$newDesc}";
        }
        if ($before['category_id'] !== $ticket->category_id) {
            $oldC = Category::find($before['category_id'])->nome ?? '—';
            $newC = $ticket->category->nome ?? '—';
            $changes[] = "Categoria: '{$oldC}' → '{$newC}'";
        }
        if ($before['type_id']     !== $ticket->type_id) {
            $oldT = Type::find($before['type_id'])->nome ?? '—';
            $newT = $ticket->type->nome ?? '—';
            $changes[] = "Tipo: '{$oldT}' → '{$newT}'";
        }
        if (!empty($changes)) {
            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'user_id'   => Auth::id(),
                'tipo_acao' => 'edicao_ticket',
                'descricao' => implode(' | ', $changes),
            ]);
        }

        return redirect()
            ->route('tickets.show', $ticket->id)
            ->with('success', 'Ticket atualizado com sucesso!');
    }

    public function show($id)
    {
        //        $ticket = Ticket::with(['usuario', 'tecnico', 'category', 'type', 'histories' => function($query) {
        //            $query->orderBy('created_at', 'asc'); // Ordena por data crescente
        //        }, 'histories.user'])->findOrFail($id);
        $ticket = Ticket::with([
            'usuario',
            'tecnico',
            'category',
            'type',
            'histories' => fn($q) => $q->orderBy('created_at', 'asc'),
            'histories.user'
        ])->findOrFail($id);

        $users = User::where('role_id', '!=', null)->get();
        $categories = Category::orderBy('nome')->get();  // ⬅️ para o select do modal
        $types = Type::orderBy('nome')->get();

        return view('tickets.show', compact('ticket', 'users', 'categories', 'types'));
    }


    // Atribuir técnico
    // assignTechnician()
    public function assignTechnician(Request $request, $id)
    {
        $request->validate([
            'tecnico_id' => 'required',
        ]);

        $ticket = Ticket::findOrFail($id);
        $novoTecnico = User::find($request->tecnico_id)->name;

        $ticket->tecnico_id = $request->tecnico_id;
        $ticket->status = 'andamento';
        $ticket->save();

        // 🔔 Enviar e-mail para o técnico designado
        Mail::to($ticket->tecnico->email)->send(new TecnicoAlteradoMail($ticket));

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'tipo_acao' => 'atribuicao_tecnico',
            'descricao' => "Usuário atribuiu o chamado ao técnico {$novoTecnico}"
        ]);

        return redirect()->route('tickets.show', $id)->with('success', 'Técnico atribuído com sucesso!');
    }


    public function updateTechnician(Request $request, $id)
    {
        $request->validate([
            'tecnico_id' => 'required',
        ]);

        $ticket = Ticket::findOrFail($id);
        $antigoTecnico = $ticket->tecnico ? $ticket->tecnico->name : 'Nenhum';
        $novoTecnico = User::find($request->tecnico_id)->name;


        $ticket->tecnico_id = $request->tecnico_id;
        $ticket->save();

        Mail::to($ticket->tecnico->email)->send(new TecnicoAlteradoMail($ticket));

        // Registra no histórico
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'tipo_acao' => 'alteração_tecnico',
            'descricao' => "Técnico alterado de {$antigoTecnico} para {$novoTecnico}"
        ]);

        return redirect()->route('tickets.show', $id)->with('success', 'Técnico atualizado com sucesso!');
    }

    // Marcar como concluído
    public function markAsCompleted(Request $request, $ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $usuario_responsavel = Auth::user()->name;

        $ticket->status = 'resolvido';
        $ticket->descricao_resolucao = $request->descricao_resolucao;
        $ticket->resolved_at = now(); // ⬅️ importante p/ métricas
        $ticket->save();

        Mail::to($ticket->usuario->email)->send(new ChamadoResolvidoMail($ticket));

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'tipo_acao' => 'conclusao_chamado',
            'descricao' => "Chamado concluído por {$usuario_responsavel}. Procedimento: {$request->descricao_resolucao}"
        ]);

        return redirect()->route('tickets.show', $ticketId)->with('success', 'Chamado marcado como concluído!');
    }

    public function markAsPending(Request $request, $ticketId)
    {
        $ticket = Ticket::find($ticketId);
        $usuario_responsavel = Auth::user()->name;

        $ticket->status = 'pendente';
        $ticket->pendencia = $request->pendencia;
        $ticket->save();

        // 🔔 Enviar e-mail para o usuário dono do chamado
        Mail::to($ticket->usuario->email)->send(new ChamadoPendenteMail($ticket));

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'tipo_acao' => 'marcar_pendencia',
            'descricao' => "O chamado foi marcado como 'Pendente' por {$usuario_responsavel}. Motivo: {$request->pendencia}"
        ]);

        return redirect()->route('tickets.show', $ticketId)->with('success', 'Chamado marcado como pendente...');
    }
}
