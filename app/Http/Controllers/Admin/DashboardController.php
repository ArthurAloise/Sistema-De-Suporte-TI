<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count(); // Quantidade de usuários cadastrados
        $openTicketsCount = Ticket::whereIn('status',  ['aberto', 'andamento'])->count(); // Soma de chamados abertos
        return view('admin.dashboard', compact('userCount', 'openTicketsCount'));
    }
}
