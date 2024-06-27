<?php

namespace App\Http\Controllers\api;

use Exception;
use Stripe\Stripe;
use App\Models\Payment;
use App\Models\Receipt;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use App\Models\ClientRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StripePaymentController extends Controller
{
    public function createPaymentIntent(Request $request) {
        $request->validate([
            'client_request_id' => 'required|exists:client_requests,id',
        ]);
        $clientRequest = ClientRequest::find($request->client_request_id);
        $vendorServiceOffering = $clientRequest->vendorServiceOffering;
        // Create a payment record in the database
        $payment = Payment::create([
            'client_id' => $clientRequest->client_id,
            'vendor_id' => $vendorServiceOffering->vendor_id,
            'service_id' => $vendorServiceOffering->subservice->service_id, // Assuming subservice belongs to a service
            'subservice_id' => $vendorServiceOffering->subservice_id,
            'amount' => $vendorServiceOffering->price,
            'payment_status' => 'pending',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));
        // Create a payment intent
        $paymentIntent = PaymentIntent::create([
            'amount' => $vendorServiceOffering->price * 100, // amount in cents
            'currency' => 'usd',
            'customer' => 'cus_QMzUhvsVwUMyQk',
            'payment_method' => $request->payment_method,
            'payment_method_types' => ['card'],
            'metadata' => [
                'payment_id' => $payment->id,
            ],
        ]);

        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
            'payment_intent_id' => $paymentIntent->id,
            'vendor_id' => $vendorServiceOffering->vendor_id, // Added vendor ID to track the vendor
            'amount' => $vendorServiceOffering->price,
        ]);

    }


     //handle payment of client
    public function handlePaymentSuccess(Request $request) {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'payment_id' => 'required|exists:payments,id',
        ]);

        $paymentId = $request->payment_id;
        $stripePaymentIntentId = $request->payment_intent_id;
        $paymentMethodId = $request->payment_method;

        try {
            // Verify the payment status with Stripe using PaymentIntent ID
            Stripe::setApiKey(config('services.stripe.secret'));
            $paymentIntent = PaymentIntent::retrieve($stripePaymentIntentId);
            $confirmedPaymentIntent = $paymentIntent->confirm([
                'payment_method' => $paymentMethodId,
            ]);
            // dd($paymentIntent);
            // Check if the payment was successful
            if ($paymentIntent->status !== 'succeeded') {
                return response()->json(['error' => 'Payment not successful'], 400);
            }

            // Use DB transaction to ensure atomic operations
            DB::transaction(function () use ($paymentId, $confirmedPaymentIntent) {
                $payment = Payment::find($paymentId);

                if ($payment) {
                    // Update payment status to 'completed'
                    $payment->payment_status = 'completed';
                    $payment->save();
                    dd($payment);
                    // Create receipt
                    $receipt = new Receipt();
                    $receipt->payment_id = $payment->id;
                    $receipt->client_name = $payment->client->name;
                    $receipt->vendor_name = $payment->vendor->name;
                    $receipt->service_name = $payment->service->name;
                    $receipt->subservice_name = $payment->subservice->name;
                    $receipt->amount = $payment->amount;
                    $receipt->save();
                } else {
                    throw new \Exception("Payment not found");
                }
            });

            return response()->json(['message' => 'Payment successful and receipt generated']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
