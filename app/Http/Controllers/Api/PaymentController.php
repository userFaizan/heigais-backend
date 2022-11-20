<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transection;
use App\Models\Walet;
use Braintree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function get_client_token(Request $request){
        // if using Braintree
        /*$gateway = new Braintree\Gateway([
            'environment' => config('services.braintree.environment'),
            'merchantId' => config('services.braintree.merchant_id'),
            'publicKey' => config('services.braintree.public_key'),
            'privateKey' => config('services.braintree.private_key')
        ]);
        $clientToken = $gateway->clientToken()->generate();*/

        /* if using stripe */
        $request->validate([
            'amount' => ['required'],
            'currency' => ['required', 'string'],
        ]);
        // $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
         $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $intent = $stripe->paymentIntents->create([
            'amount' => ((float)$request->amount)*100,
            'currency' => $request->currency,
        ]);
        $client_secret = $intent->client_secret;
        return response()->json(['token'=>$client_secret],200);
    }

    public function checkout(Request $request){
        /* for braintree there must be a nounce sended from the client side */
        /* $gateway = new Braintree\Gateway([
            'environment' => config('services.braintree.environment'),
            'merchantId' => config('services.braintree.merchant_id'),
            'publicKey' => config('services.braintree.public_key'),
            'privateKey' => config('services.braintree.private_key')
         ]);
         $result = $gateway->transaction()->sale([
            'amount' => $request->amount,
            'paymentMethodNonce' => $request->nounce,
            // 'paymentMethodNonce' => Braintree\Test\Nonces::$transactable,
            'options' => [
              'submitForSettlement' => True
            ]
        ]);*/
        Log::error($request->all());

        $transection = Transection::create([
            'walet_id'=>$request->walet_id,
            'user_id'=>auth()->id(),
            'credit'=>$request->credit,
            'amount'=>$request->amount
        ]);

        return response()->json([
            'message'=>'Payment Success!'
        ],200);
     }

    public function pay(Request $request){
        $walet = Walet::where('user_id',auth()->id())->first();
        if($walet->balance >= $request->amount){
            $transection = Transection::create(['walet_id'=>$walet->id,'user_id'=>auth()->id(),'type_id'=>2,'amount'=>$request->amount,'purpose'=>'some pyment event']);
            if($transection->type_id == 2){
                $walet->update(['balance'=> $walet->balance - $request->amount]);
            }
            return response()->json(['message'=>'paid successfully'],200);
        }else{
            return response()->json(['message'=>'insufficient balance'],403);
        }
    }
}
