<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use GuzzleHttp\Client;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function trueResponse($data, $message = '')
    {
        $trueResponse["status"] = true;
        $trueResponse["message"] = $message;
        if ($data instanceof \Illuminate\Pagination\Paginator || $data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $trueResponse["data"] = $data->toArray()['data'];

            $meta['current_page'] = $data->toArray()['current_page'];
            $meta['per_page'] = $data->toArray()['per_page'];
            $meta['total_page'] = $data->toArray()['last_page'];
            $meta['total_data'] = $data->toArray()['total'];
            $trueResponse["meta"]['pagination'] = $meta;
        } else {
            $trueResponse["data"] = $data;
            $trueResponse["meta"] = null;
        }
        $trueResponse["errors"] = [];

        return response()->json($trueResponse, Response::HTTP_OK);
    }

    protected function failResponse($message, $errors = [], $isArray = false)
    {
        if($errors instanceof MessageBag){
            $msgbag = $errors;
            $errors = [];
            foreach($msgbag->messages() as $key => $value) $errors[] = [ 'attribute' => $key, 'text' => $value[0]];
        }else{
            $formattedError = [];
            foreach($errors as $key => $value){
                if(is_array($value)){
                    $text = $value[0];
                }else{
                    $text = $value;
                }
                $formattedError[] = [ 'attribute' => $key, 'text' => $text];
            };
            $errors = $formattedError;
        }
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $isArray ? [] : null,
            'meta' => null,
            'errors' => $errors
        ], Response::HTTP_OK);
    }

    protected function wallet($user_id)
    {
        $wallet = Wallet::where('user_id', $user_id)->first();
        if ($wallet == null) {
            $wallet = new Wallet();
            $wallet->user_id = $user_id;
            $wallet->balance = 0;
            $wallet->save();
        }

        return $wallet;
    }

    protected function topup($user_id, $amount)
    {
        $wallet = $this->wallet($user_id);
        DB::beginTransaction();
        try{
            $transaction = new WalletTransaction;
            $transaction->to_id  = $wallet->id;
            $transaction->amount = $amount;
            $transaction->type   = 'TOPUP';
            $transaction->save();

            $wallet->balance += $amount;
            $wallet->save();

            DB::commit();
        }catch(Throwable $th) {
            Log::info($th);
            return false;
        }

        return $wallet;
    }

    protected function transfer($from, $to, $amount)
    {
        $wallet_from = $this->wallet($from);
        $wallet_to = $this->wallet($to);

        DB::beginTransaction();
        try{
            $transaction = new WalletTransaction;
            $transaction->from_id  = $wallet_from->id;
            $transaction->to_id  = $wallet_to->id;
            $transaction->amount = $amount;
            $transaction->type   = 'TRANSFER';
            $transaction->save();

            $wallet_from->balance -= $amount;
            $wallet_from->save();

            $wallet_to->balance += $amount;
            $wallet_to->save();

            DB::commit();
        }catch(Throwable $th) {
            Log::info($th);
            return false;
        }

        return $wallet_from;
    }

    public function sendNotification($data)
    {
        
        try {
            $client = new Client(['headers' => [
                'Accept' => 'application/json',
                'api-key' => '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',
                'content-type' => 'application/json',
            ]]);
            $sender["name"] = "Tujju Techinal Test - Rizky Maulana Fauzi";
            $sender["email"] = "rizkywarhole@gmail.com";

            $data["subject"] = "Notification";
            $data["htmlContent"] = view('email', $data)->render();

            $data["sender"] = $sender;
            $data["to"][0]["name"] = $data['name'];
            $data["to"][0]["email"] = $data['email'];
            $res = $client->post('https://api.sendinblue.com/v3/smtp/email', ["json" => $data]);
            \Log::info(json_encode($data));
        } catch (\Throwable $th) {
            \Log::info($th);
        }
    }
}
