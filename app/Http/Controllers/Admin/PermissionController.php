<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    // Construtor para autenticação de admin
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:acessar_permissoes');  // Permissão de acesso (se você tiver um sistema de autorização)
    }

    // Mostrar a listagem de permissões
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    // Mostrar o formulário para criar uma nova permissão
    public function create()
    {
        return view('admin.permissions.create');
    }

    // Criar uma nova permissão
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions|max:255',
//            'description' => 'nullable|string',
        ]);

        Permission::create([
            'name' => $request->name,
//            'description' => $request->description,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permissão criada com sucesso!');
    }

    // Mostrar o formulário para editar uma permissão
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    // Atualizar a permissão
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|max:255|unique:permissions,name,' . $permission->id,
//            'description' => 'nullable|string',
        ]);

        $permission->update([
            'name' => $request->name,
//            'description' => $request->description,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permissão atualizada com sucesso!');
    }

    // Excluir uma permissão
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permissão excluída com sucesso!');
    }
}
