<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPaymentIntent($request, $vendorServiceOffering, $payment)
    {
        // create the payment intent fetch all the information
        $paymentIntent =  PaymentIntent::create([
                'amount' => $vendorServiceOffering->price * 100, // amount in cents
                'currency' => 'usd',
                'customer' => 'cus_QMzUhvsVwUMyQk',
                'payment_method' => $request->payment_method,
                'payment_method_types' => ['card'],
                'metadata' => [
                    'payment_id' => $payment->id,
                ],
        ]);
        //return the response to the controller of paymentIntent response
        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
            'payment_intent_id' => $paymentIntent->id,
            'vendor_id' => $vendorServiceOffering->vendor_id, // Added vendor ID to track the vendor
            'amount' => $vendorServiceOffering->price,
        ]);
    }
}
