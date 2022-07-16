<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WalletTransaction;
use Auth;
use Validator;

class TransferController extends Controller
{
    public function create()
    {
        $users = User::whereHas('wallet')->where('id', '!=', Auth::id())->get();
        return view('components.transfer', compact('users'))->render();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'amount'  => 'required|numeric|min:10000',
            'to_id' => 'required'
        ]);

        if ($validator->fails()) {  
            return response()->json(['status'=>false,"message"=>"min transfer is 10000"]);
        }

        $amount  = $request->amount;
        $to_id = $request->to_id;
        $transfer = $this->transfer(Auth::id(), $to_id, $amount);
        if($transfer == false) {
            return response()->json(['status'=>false ,'message'=>'Transfer failed, please try again']);
        }

        return response()->json(['status'=>true,'message'=> 'Transfer success']);
    }
}
