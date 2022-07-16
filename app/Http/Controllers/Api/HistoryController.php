<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use Auth;

class HistoryController extends Controller
{
    public function history()
    {
        $transaction = WalletTransaction::with('from.user','to.user')->where('from_id', Auth::user()->wallet->id)
                                        ->orWhere('to_id', Auth::user()->wallet->id)
                                        ->paginate(12);

        return $this->trueResponse($transaction);
    }
}
