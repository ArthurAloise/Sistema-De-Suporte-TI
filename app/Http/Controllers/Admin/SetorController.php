<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setor;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSetorRequest;
use App\Http\Requests\UpdateSetorRequest;

class SetorController extends Controller
{
    // public function __construct() { $this->middleware('auth'); }

    public function index(Request $request)
    {
        $search = trim($request->get('q', ''));
        $setores = Setor::when($search, function ($q) use ($search) {
            $q->where('nome', 'LIKE', "%{$search}%")
                ->orWhere('sigla', 'LIKE', "%{$search}%");
        })
            ->orderBy('nome')
            ->paginate(10)
            ->withQueryString();

        return view('admin.setores.index', compact('setores', 'search'));
    }

    public function create()
    {
        $setor = new Setor();
        return view('admin.setores.create', compact('setor'));
    }

    public function store(StoreSetorRequest $request)
    {
        Setor::create($request->validated());
        return redirect()->route('setores.index')->with('success', 'Setor criado com sucesso.');
    }

    public function edit(Setor $setor)
    {
        return view('admin.setores.edit', compact('setor'));
    }

    public function update(UpdateSetorRequest $request, Setor $setor)
    {
        $setor->update($request->validated());
        return redirect()->route('setores.index')->with('success', 'Setor atualizado com sucesso.');
    }

    public function destroy(Setor $setor)
    {
        $setor->delete();
        return redirect()->route('setores.index')->with('success', 'Setor excluído com sucesso.');
    }
}
