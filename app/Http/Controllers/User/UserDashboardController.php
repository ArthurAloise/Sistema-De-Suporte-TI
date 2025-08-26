<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
//        $ticket = Ticket::all();
        $user = Auth::user();
        $ticket = Ticket::with(['usuario', 'tecnico' ,'category', 'type'])
            ->where('usuario_id', $user->id) // Filtrando para mostrar apenas os tickets abertos pelo próprio usuário
            ->paginate(5);

        return view('user.dashboard', compact('user', 'ticket'));
    }
}
