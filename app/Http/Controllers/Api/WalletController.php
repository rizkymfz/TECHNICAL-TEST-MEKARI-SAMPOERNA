<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Auth;

class WalletController extends Controller
{
    public function topupWallet(Request $request)
    {

        $validator = Validator::make($request->all(), [ 
            'amount'  => 'required|numeric|min:10000',
        ]);

        if ($validator->fails()) {  
            return $this->failResponse("validation data invalid.", $validator->errors());
        }

        $amount  = $request->amount;
        $topup = $this->topup(Auth::id(), $amount);
        if($topup == false) {
            return $this->falseResponse('Topup failed, please try again');
        }

        return $this->trueResponse($topup, 'Topup success');
    }

    public function transferWallet(Request $request)
    {

        $validator = Validator::make($request->all(), [ 
            'amount'  => 'required|numeric|min:10000',
            'to_id' => 'required'
        ]);

        if ($validator->fails()) {  
            return $this->failResponse("validation data invalid.", $validator->errors());
        }

        $amount  = $request->amount;
        $to_id = $request->to_id;
        $transfer = $this->transfer(Auth::id(), $to_id, $amount);
        if($transfer == false) {
            return $this->falseResponse('transfer failed, please try again');
        }

        $user = User::find($to_id);
        $res = [
            'to' => $user->name,
            'amount' => $amount,
        ];

        return $this->trueResponse($res, 'transfer success');
    }
}
