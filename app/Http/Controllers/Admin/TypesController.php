<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use App\Services\SlaService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTypeRequest;
use App\Http\Requests\UpdateTypeRequest;

class TypesController extends Controller
{
    // Opcional: descomente se quiser exigir login
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index(Request $request)
    {
        $search = trim($request->get('q', ''));
        $types = Type::when($search, fn($q) =>
        $q->where('nome', 'LIKE', "%{$search}%")
        )
            ->orderBy('nome')
            ->paginate(10)
            ->withQueryString();

        return view('admin.types.index', compact('types', 'search'));
    }

    public function create()
    {
        $type = new Type();
        return view('admin.types.create', compact('type'));
    }

    public function store(StoreTypeRequest $request)
    {
        $type = Type::create($request->validated());
        SlaService::forgetType($type->nome);

        return redirect()->route('types.index')->with('success', 'Tipo criado com sucesso.');
    }

    public function edit(Type $type)
    {
        return view('admin.types.edit', compact('type'));
    }

    public function update(UpdateTypeRequest $request, Type $type)
    {
        $type->update($request->validated());
        SlaService::forgetType($type->nome);

        return redirect()->route('types.index')->with('success', 'Tipo atualizado com sucesso.');
    }

    public function destroy(Type $type)
    {
        $type->delete();
        return redirect()->route('types.index')->with('success', 'Tipo excluído com sucesso.');
    }
}
