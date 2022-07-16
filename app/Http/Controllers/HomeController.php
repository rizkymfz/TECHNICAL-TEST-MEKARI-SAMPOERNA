<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\WalletTransaction;

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
        return view('main');
    }

    public function history()
    {
        $data = [
            'transaction'=> WalletTransaction::where('from_id', Auth::user()->wallet->id)
                            ->orWhere('to_id', Auth::user()->wallet->id)->get(),
        ];
        return view('components.dashboard', $data)->render();
    }
}
