<?php

namespace App\Http\Controllers;

use App\Models\Tournement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //$toernooien = Tournement::all();
        $toernooien = Tournement::whereHas('users', function($q) {
            $q->where('tournement_user.user_id', Auth::user()->id);
        })->get();
        //$toernooien = Tournement::with('users')->get();
        return view('home', compact('toernooien'));
    }
}
