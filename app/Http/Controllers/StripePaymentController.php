<?php

      

namespace App\Http\Controllers;

use App\Models\JobBid;
use App\Models\Payment;
use Illuminate\Http\Request;

use Stripe;

use Illuminate\View\View;

use Illuminate\Http\RedirectResponse;

       

class StripePaymentController extends Controller

{

    /**

     * success response method.

     *

     * @return \Illuminate\Http\Response

     */

    public function stripe(): View

    {

        return view('stripe');

    }
    public function stripePayment($bidId)
    {
        $bid = JobBid::findOrFail($bidId);
        return view('stripe', ['bid' => $bid]);
    }
    public function processStripePayment(Request $request)
    {
        $bidId = $request->get('bid_id');
        $bid = JobBid::findOrFail($bidId);
    
        Stripe::setApiKey(env('STRIPE_SECRET'));
    
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $bid->bid_amount * 100, // Amount in cents
                'currency' => 'usd',
                'payment_method' => $request->get('payment_method_id'),
                'confirmation_method' => 'manual',
                'confirm' => true,
                'metadata' => [
                    'bid_id' => $bid->id,
                    'job_id' => $bid->job_id,
                    'client_id' => $bid->job->client_id,
                    'technician_id' => $bid->technician_id
                ]
            ]);
    
            // Update bid and job status
            $bid->update(['status' => 'paid']);
            $bid->job->update(['status' => 'completed']);
    
            // Create payment record
            Payment::create([
                'job_id' => $bid->job_id,
                'client_id' => $bid->job->client_id,
                'technician_id' => $bid->technician_id,
                'amount' => $bid->bid_amount,
                'payment_method' => 'credit_card',
                'payment_status' => 'completed',
                'transaction_id' => $paymentIntent->id,
                'payment_date' => now()
            ]);
    
            return response()->json([
                'success' => true,
                'redirect_url' => route('page.technicians.contract')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment failed.'
            ], 500);
        }
    }

    /**

     * success response method.

     *

     * @return \Illuminate\Http\Response

     */

    public function stripePost(Request $request): RedirectResponse

    {

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

      

        Stripe\Charge::create ([

                "amount" => 10 * 100,

                "currency" => "usd",

                "source" => $request->stripeToken,

                "description" => "Test payment from itsolutionstuff.com." 

        ]);

                

        return back()

                ->with('success', 'Payment successful!');

    }

}

