<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Wallet;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
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
        $d['access_token'] =  $user->createToken(env('APP_NAME'))->plainTextToken;

        return $this->trueResponse($d, 'registration success');
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [ 
            'email'  => 'required|email',
            'password'  => 'required',
        ]);

        if ($validator->fails()) {  
            return $this->failResponse("validation data invalid.", $validator->errors());
        }

        $credentials = ['email' => $request->email, 'password' => $request->password];        
       
        if(Auth::attempt($credentials)){ 

            $user = Auth::user(); 
            $d['access_token'] =  $user->createToken(env('APP_NAME'))->plainTextToken; 
   
            return $this->trueResponse($d, 'login success');

        } else{
            return $this->failResponse('Invalid email or password');
        }
    }

    public function logout(Request $request)
    {

        Auth::user()->tokens()->delete();

        return $this->trueResponse(null, 'Logout success');
    }

    public function authMe()
    {
        if (auth('sanctum')->check()){
            $user = auth('sanctum')->user();
            $user->wallet = auth('sanctum')->user()->wallet;

            return $this->trueResponse($user);
        }
    }
    
    
}
