<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EscrowPayment;
use App\Models\JobBid;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    
   // Function to initiate the payment
   public function payment(Request $request) {
    $provider = new PayPalClient;
    $provider->setApiCredentials(config('paypal'));

    $response = $provider->createOrder([
        "intent" => "CAPTURE",
        "purchase_units" => [[
            "amount" => [
                "currency_code" => "USD",
                "value" => $request->price
            ]
        ]]
    ]);

    if(isset($response['id'])) {
        return response()->json(['id' => $response['id']]);
    }

    return response()->json(['error' => 'Failed to create order'], 500);
}

public function success(Request $request)
{
    $paymentData = $request->input('paymentDetails');
    $orderID = $request->input('orderID');

    // Create escrow payment record
    $payment = EscrowPayment::create([
        'transaction_id' => $orderID,
        'job_bid_id' => session('bid_id'),
        'amount' => $paymentData['purchase_units'][0]['amount']['value'],
        'status' => 'completed',
        'payment_method' => 'paypal'
    ]);

    if ($payment) {
        // Update job bid status
        $jobBid = JobBid::find($payment->job_bid_id);
        if ($jobBid) {
            $jobBid->status = 'accepted';
            $jobBid->save();

            // Update job posting status
            $jobBid->jobPosting->status = 'completed';
            $jobBid->jobPosting->save();
        }
    }

    session(['current_step' => 4]); // Move to the payment success step
    return redirect()->route('page.technicians.form')->with('success', 'Payment processed successfully!');
}


public function cancel(Request $request)
{
    return redirect()->route('page.technicians.form')->with('error', 'Payment was canceled.');
}

public function showPaypalPage()
{
    return view('payment');
}
}