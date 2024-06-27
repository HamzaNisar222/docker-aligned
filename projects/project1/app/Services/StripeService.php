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

    public function createPaymentIntent($amount, $currency = 'usd')
    {
        return PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
        ]);
    }
}
