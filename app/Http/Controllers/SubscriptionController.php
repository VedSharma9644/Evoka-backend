<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\EventComment;
use App\Models\EventParticipation;
use App\Models\Subscription;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    function getPaypalAccessToken()
{
    $response = Http::asForm()->withBasicAuth(
        env('PAYPAL_CLIENT_ID'),
        env('PAYPAL_SECRET')
    )->post(env('PAYPAL_BASE_URL') . '/v1/oauth2/token', [
        'grant_type' => 'client_credentials',
    ]);

    return $response->json()['access_token'];
}

    public function start(Request $req){
        $uid = $req->user()->id;
        $plan = $req->plan;
            $fee = 0;
 $month = 1;
        if($plan == "1month"){
            $fee = 0;
            $month = 1;
        }elseif($plan == "3month"){
             $fee = 30;
            $month = 3;
        }elseif($plan == "6month"){
             $fee = 50;
            $month = 6;
        }
        elseif($plan == "12month"){
             $fee = 90;
            $month = 12;
        }
        if($fee ==0){
            // 
            // Checking if alrady availed.
            $check = Subscription::where('user_id',$uid)->where('price',0)->count();
            if($check>0){
                return response(['message'=>'You already used free trail!',500]);
            }
       Subscription::create([
    'user_id' => $uid,
    'start_date' => Carbon::now(),
    'end_date' => Carbon::now()->addMonth(),
    'subscription_type' => $plan, // Replace with actual type
    'price' => $fee, // Replace with actual price
]);
                return response(['message'=>$month.' month subscription is started!',500]);

            // 
        }else{
                    $accessToken = self::getPaypalAccessToken();

    $response = Http::withToken($accessToken)
        ->post(env('PAYPAL_BASE_URL') . '/v2/checkout/orders', [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'EUR',
                    'value' => $fee,
                ],
            ]],
            'application_context' => [
                'return_url' => url('paypal-subscription/success/' . $plan.'/'. $req->user()->id),
                'cancel_url' => url('paypal/cancel'),
            ]
        ]);
         $redirect =  (collect($response->json()['links'])->firstWhere('rel', 'approve')['href']);
            return response()->json(['redirect' => $redirect ], 200);
        }
    }
    public function paypal_success($plan,$uid,Request $request){
           $orderId = $request->query('token');
        $accessToken = self::getPaypalAccessToken();

$response = Http::withToken($accessToken)
    ->withBody('', 'application/json') // <-- explicitly empty body
    ->post(env('PAYPAL_BASE_URL') . "/v2/checkout/orders/{$orderId}/capture");
  if($plan == "1month"){
            $fee = 0;
            $month = 1;
        }elseif($plan == "3month"){
             $fee = 30;
            $month = 3;
        }elseif($plan == "6month"){
             $fee = 50;
            $month = 6;
        }
        elseif($plan == "12month"){
             $fee = 90;
            $month = 12;
        }
            Subscription::create([
    'user_id' => $uid,
    'start_date' => Carbon::now(),
    'end_date' => Carbon::now()->addMonth(),
    'subscription_type' => $plan, // Replace with actual type
    'price' => $fee, // Replace with actual price
]);
   return redirect(env('FRONTEND_URL').'payment/success');

    }
    public function activePlan(Request $req){
$uid = $req->user()->id;
        $activeSubscription = Subscription::where('user_id', $uid)
    ->where('start_date', '<=', Carbon::now())
    ->where('end_date', '>=', Carbon::now())
    ->first();
    if($activeSubscription){
    return response()->json(['data'=>$activeSubscription ]);

    }
    return response()->json(['message'=>'No plan subscribed!'],500);
    }
}