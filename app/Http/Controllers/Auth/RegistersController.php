<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Wallet;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistersController extends Controller
{

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(), [ 
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password'  => 'required|min:8',
        ]);

        if ($validator->fails()) {  
            return $this->failResponse("validation data invalid.", $validator->errors());
        }

        DB::beginTransaction();
        try{

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $wallet = new Wallet;
            $wallet->user_id = $user->id;
            $wallet->balance  = 0;
            $wallet->save();

            DB::commit();

        } catch (\Throwable $th) {
            Log::info($th);
            return $this->failResponse("server error");
        }
        Auth::login($user);
        return redirect()->route('home');
    }
}
