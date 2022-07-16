<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\WalletTransaction;

class TopupController extends Controller
{
    public function create()
    {
        return view('components.topup')->render();
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [ 
            'amount'  => 'required|numeric|min:10000',
        ]);

        if ($validator->fails()) {  
            return response()->json(['status'=>false,"message"=>"min topup is 10000"]);
        }

        $amount  = $request->amount;
        $topup = $this->topup(Auth::id(), $amount);
        if($topup == false) {
            return response()->json(['status'=>false ,'message'=>'Topup failed, please try again']);
        }

        return response()->json(['status'=>true,'message'=> 'Topup success']);
    }
}
