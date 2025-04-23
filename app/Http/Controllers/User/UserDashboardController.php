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
        $ticket = Ticket::with(['usuario', 'tecnico' ,'category', 'type'])->get();

        return view('user.dashboard', compact('user', 'ticket'));
    }
}
