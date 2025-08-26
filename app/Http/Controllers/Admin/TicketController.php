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

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required',
            'prioridade' => 'required',
            'category_id' => 'required',
            'type_id' => 'required'
        ]);

        Ticket::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'prioridade' => $request->prioridade,
            'category_id' => $request->category_id,
            'type_id' => $request->type_id,
            'usuario_id' => Auth::id()
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Ticket criado com sucesso!');
    }

    public function show($id)
    {
        $ticket = Ticket::with(['usuario', 'tecnico', 'category', 'type', 'histories' => function($query) {
            $query->orderBy('created_at', 'asc'); // Ordena por data crescente
        }, 'histories.user'])->findOrFail($id);
        $users = User::where('role_id', '!=', null)->get();

        return view('tickets.show', compact('ticket', 'users'));
    }


    // Atribuir técnico
    public function assignTechnician(Request $request, $id)
    {
        $request->validate([
            'tecnico_id' => 'required',
        ]);

        $ticket = Ticket::findOrFail($id);
        $novoTecnico = User::find($request->tecnico_id)->name;

        $ticket->tecnico_id = $request->tecnico_id;
        $ticket->status = 'andamento'; // Atualiza o status
        $ticket->save();

        // Registra no histórico
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'tipo_acao' => 'atribuicao_tecnico',
            'descricao' => "Usuário Atribuiu o chamado ao técnico {$novoTecnico}"
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
        $ticket = Ticket::find($ticketId);
        $usuario_responsavel = Auth::user()->name;

        $ticket->status = 'resolvido'; // Alterando status para "Concluído"
        $ticket->descricao_resolucao = $request->descricao_resolucao; // Adicionando a descrição do procedimento realizado
        $ticket->save();

        // Registra no histórico
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'tipo_acao' => 'conclusao_chamado',
            'descricao' => "Chamado concluído por {$usuario_responsavel}. Procedimento realizado: {$request->descricao_resolucao}"
        ]);

        return redirect()->route('tickets.show', $ticketId)->with('success', 'Chamado marcado como concluído!');
    }

    public function markAsPending(Request $request, $ticketId)
    {
        $ticket = Ticket::find($ticketId);
        $usuario_responsavel = Auth::user()->name;

        $ticket->status = 'pendente'; // Alterando status para "Pendente"
        $ticket->pendencia = $request->pendencia; // Adicionando a descrição da pendência
        $ticket->save();

        // Registra no histórico
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'tipo_acao' => 'marcar_pendencia',
            'descricao' => "O chamado foi marcado como 'Pendente' por {$usuario_responsavel}. Motivo: {$request->pendencia}"
        ]);

        return redirect()->route('tickets.show', $ticketId)->with('success', 'Chamado marcado como pendente...');
    }
}
