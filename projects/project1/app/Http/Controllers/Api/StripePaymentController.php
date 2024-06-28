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
use App\Services\StripeService;

class StripePaymentController extends Controller
{
    // created the custom service class for payment to handle the request
    protected $stripeService;
    public function __construct(StripeService $stripeService) {
        $this->stripeService = $stripeService;
    }

    // Creating the paymentIntent from the client informationx
    public function createPaymentIntent(Request $request) {
        $request->validate([
            'client_request_id' => 'required|exists:client_requests,id',
        ]);
        // finding the request from CilentRequst::Model
        $clientRequest = ClientRequest::find($request->client_request_id);
        // Checking the status of the clientRequest in table
        $check = ClientRequest::checkClientRequest($clientRequest);
        if ($check) {
            return $check;
        }
        //this check if the request have approved status and payemnt_staus false then proceed with payment
        if ($clientRequest->status == 'approved' && $clientRequest->payment_status === false) {
            $vendorServiceOffering = $clientRequest->vendorServiceOffering;
            // Create a payment record in the database
            $payment = Payment::createPayment($vendorServiceOffering, $clientRequest);
            // Create a payment intent
            $paymentIntent = $this->stripeService->createPaymentIntent($request, $vendorServiceOffering, $payment);
            return $paymentIntent;
        }

    }


     //handle payment of client
    public function handlePaymentSuccess(Request $request) {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'payment_id' => 'required|exists:payments,id',
        ]);

        $paymentId = $request->payment_id;
        $stripePaymentIntentId = $request->payment_intent_id;

        try {
            // Verify the payment status with Stripe using PaymentIntent ID
            Stripe::setApiKey(config('services.stripe.secret'));
            $paymentIntent = PaymentIntent::retrieve($stripePaymentIntentId);
            if ($paymentIntent->status === 'requires_confirmation') {
                $paymentIntent->confirm();
            }
            // Check if the payment was successful
            if ($paymentIntent->status !== 'succeeded') {
                return response()->json(['error' => 'Payment not successful'], 400);
            }

            // Use DB transaction to ensure atomic operations
            DB::transaction(function () use ($paymentId) {
                $payment = Payment::find($paymentId);

                if ($payment) {
                    // Update payment status to 'completed'
                    $payment->payment_status = 'completed';
                    $payment->save();

                    // changing the payemnt_status in client_reqiuest table after payment is success.
                    $clientRequest = $payment->clientRequest;
                    if ($clientRequest) {
                        $clientRequest->payment_status = 'true';
                        $clientRequest->save();
                    }
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
            // retuern the successful response when payemnt is successeded
            return response()->json(['message' => 'Payment successful and receipt generated']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
