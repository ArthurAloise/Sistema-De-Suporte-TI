<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:acessar_perfis');
    }

    // Método para mostrar a listagem de roles
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    // Método para mostrar o formulário de criação de role
    public function create()
    {
        $permissions = Permission::all();  // Buscar todas as permissões
        return view('admin.roles.create', compact('permissions'));
    }

    // Método para salvar a role
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|max:255',
//            'description' => 'nullable|string',
            'permissions' => 'array',  // Receber as permissões como array
        ]);

        $role = Role::create([
            'name' => $request->name,
//            'description' => $request->description,
        ]);

        // Atribuindo permissões à role
        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Perfil criado com sucesso!');
    }

    // Método para editar a role
    public function edit(Role $role)
    {
        $permissions = Permission::all();  // Buscar todas as permissões
        $rolePermissions = $role->permissions->pluck('id')->toArray(); // Buscar permissões atuais da role
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    // Método para atualizar a role
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|max:255|unique:roles,name,' . $role->id,
//            'description' => 'nullable|string',
            'permissions' => 'array',
        ]);

        $role->update([
            'name' => $request->name,
//            'description' => $request->description,
        ]);

        if ($request->permissions) {
            // Primeiramente, limpamos as permissões antigas
            $role->permissions()->detach();

            // Agora, atribuimos as novas permissões
            foreach ($request->permissions as $permissionId) {
                $role->permissions()->attach($permissionId);
            }
        }

        return redirect()->route('roles.index')->with('success', 'Perfil atualizado com sucesso!');
    }

    // Método para excluir a role
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Perfil excluído com sucesso!');
    }
}
