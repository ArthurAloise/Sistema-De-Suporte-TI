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

    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|unique:roles|max:255',
            'permissions'    => 'array',
            'permissions.*'  => 'integer|exists:permissions,id',
        ]);

        $role = Role::create(['name' => $request->name]);

        $ids = $request->input('permissions', []);
        $role->permissions()->sync($ids);

        return redirect()->route('roles.index')->with('success', 'Perfil criado com sucesso!');
    }

    public function edit(Role $role)
    {
        $permissions     = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'           => 'required|max:255|unique:roles,name,' . $role->id,
            'permissions'    => 'array',
            'permissions.*'  => 'integer|exists:permissions,id',
        ]);

        $role->update(['name' => $request->name]);

        $ids = $request->input('permissions', []);
        $role->permissions()->sync($ids);

        return redirect()->route('roles.index')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Perfil excluído com sucesso!');
    }
}
