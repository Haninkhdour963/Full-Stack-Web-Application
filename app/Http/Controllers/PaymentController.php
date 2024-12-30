<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    
   // Function to initiate the payment
   public function payment(Request $request) {
    $provider = new PayPalClient;

    // Set PayPal credentials
    $provider->setApiCredentials(config('paypal'));

    // Get PayPal access token
    $paypalToken = $provider->getAccessToken();

    // Prepare PayPal order data
    $response = $provider->createOrder([
        "intent" => "CAPTURE",
        "application_context" => [
            "return_url" => route('success'),  // Success URL
            "cancel_url" => route('cancel')   // Cancel URL
        ],
        "purchase_units" => [
            [
                "amount" => [
                    "currency_code" => "USD",
                    "value" => $request->price  // Pass price dynamically
                ]
            ]
        ]
    ]);
    

    // Check if PayPal response contains the order ID
    if (isset($response['id']) && $response['id'] != null) {
        // Find the approval URL from PayPal response
        foreach ($response['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return redirect()->away($link['href']);  // Redirect to PayPal approval page
            }
        }
    }

    // If response doesn't contain the order ID, return an error
    return redirect()->route('page.technicians.form')->with('error', 'Error initiating payment. Please try again.');
}

// Function to handle successful payment
public function success(Request $request) {
    $provider = new PayPalClient;

    // Set PayPal credentials
    $provider->setApiCredentials(config('paypal'));

    // Get the access token
    $paypalToken = $provider->getAccessToken();

    // Capture the payment using the token received from PayPal
    $response = $provider->capturePaymentOrder($request->token);

    // Check if the payment was successfully captured
    if (isset($response['status']) && $response['status'] == 'COMPLETED') {
        // Payment was successful, proceed with any necessary actions
        // Example: updating the database or setting a session variable
        session(['current_step' => 4]);

        // Redirect to the 'bid' page with success message
        return redirect()->route('page.technicians.bid')->with('success', 'Payment successful! You can now proceed with bidding.');
    } else {
        // If payment failed, return an error
        return redirect()->route('page.technicians.form')->with('error', 'Payment failed. Please try again.');
    }
}

// Function to handle payment cancellation
public function cancel(Request $request) {
    // Handle cancel payment scenario
    return redirect()->route('page.technicians.form')->with('error', 'Payment was canceled.');
}

// Show PayPal page (route where users are directed to initiate PayPal payments)
public function showPaypalPage() {
    return view('payment');  // Display a page for initiating PayPal payments
}
}