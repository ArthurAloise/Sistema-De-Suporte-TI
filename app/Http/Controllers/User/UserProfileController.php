<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('user.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|regex:/^\(\d{2}\) \d{5}-\d{4}$/',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Atualiza nome, email e telefone
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        // Verifica se há uma nova imagem
        if ($request->hasFile('profile_picture')) {
            // Obtém a imagem enviada
            $image = $request->file('profile_picture');

            // Converte a imagem em base64
            $imageData = base64_encode(file_get_contents($image));

            // Substitui a imagem no banco de dados (se já existir)
            $user->profile_picture = $imageData;
        }

        $user->save();
        return redirect()->route('user.profile')->with('success', 'Perfil atualizado com sucesso!');
    }
}
