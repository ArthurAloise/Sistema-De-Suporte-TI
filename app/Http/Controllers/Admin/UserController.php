<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\Setor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim($request->get('q', ''));          // termo de busca
        $setorId  = $request->get('setor_id');             // filtro por setor
        $perPage  = (int) ($request->get('per_page', 10)); // opcional: itens por página

        $users = User::with(['role','setor'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name',  'LIKE', "%{$q}%")
                        ->orWhere('email','LIKE', "%{$q}%")
                        ->orWhere('phone','LIKE', "%{$q}%");
                });
            })
            ->when($setorId, fn($query) => $query->where('setor_id', $setorId))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString(); // mantém filtros na paginação

        $setores = Setor::orderBy('nome')->get();

        return view('admin.users.index', compact('users','setores','q','setorId','perPage'));
    }

    public function create()
    {
        $roles = Role::all();
        $setores = Setor::orderBy('nome')->get();
        return view('admin.users.create', compact('roles','setores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'role_id' => 'required',
            'setor_id' => 'nullable|exists:setores,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'setor_id' => $request->setor_id,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $setores = Setor::orderBy('nome')->get();
        return view('admin.users.edit', compact('user', 'roles', 'setores'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
            'role_id' => 'required',
            'setor_id' => 'nullable|exists:setores,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'setor_id' => $request->setor_id,
            'password' => $request->password ? Hash::make($request->password) : $user->password
        ]);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso!');
    }
}

