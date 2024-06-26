<?php

namespace App\Http\Controllers\api;

use Exception;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StripePaymentController extends Controller
{
    public function stripePost(Request $request) {

        Stripe::setApiKey(env('STRIPE_SECRET'));
        // $token = $request->headers('token');
        // dd($token);
        // $paymentMethodId = $request->payment_method_id;

        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount,
            'currency' => 'usd',
            'payment_method' => 'pm_card_visa',
            'payment_method_types' => ['card'],
            'description' => $request->description,
        ]);
        $confirmPaymentIntent = $paymentIntent->confirm([
            'payment_method_data' => [
                'type' => 'card',
                'card' => [
                    'number' => $request->number,
                    'exp_month' => $request->exp_month,
                    'exp_year' => $request->exp_year,
                    'cvc' => $request->cvc,
                ],
            ],
            ]);
            return response()->json([
            'status' => 'success',
            'payment_intent' => $confirmPaymentIntent,
            ], 201);
    }

}
