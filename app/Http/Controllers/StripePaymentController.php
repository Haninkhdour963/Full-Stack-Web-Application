<?php

namespace App\Http\Controllers;

use App\Models\JobBid;
use App\Models\Payment;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StripePaymentController extends Controller
{
    public function stripe(): View
    {
        return view('stripe');
    }

    public function stripePayment($bidId)
    {
        $bid = JobBid::with('job')->findOrFail($bidId);
        return view('stripe', [
            'bid' => $bid,
            'amount' => $bid->bid_amount,
            'job_id' => $bid->job_id,
            'technician_id' => $bid->technician_id
        ]);
    }

    public function processStripePayment(Request $request)
    {
        $bidId = $request->get('bid_id');
        $bid = JobBid::findOrFail($bidId);
    
        Stripe::setApiKey(env('STRIPE_SECRET'));
    
        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $bid->bid_amount * 100,
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

            $bid->update(['status' => 'accepted']);
            $bid->job->update(['status' => 'in_progress']);

            Payment::create([
                'job_id' => $bid->job_id,
                'client_id' => $bid->job->client_id,
                'technician_id' => $bid->technician_id,
                'amount' => $bid->bid_amount,
                'payment_method' => 'credit_card',
                'payment_status' => 'completed',
                'transaction_id' => $paymentIntent->id,
                'payment_date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'redirect_url' => route('page.technicians.contract')
            ]);

        } catch (\Exception $e) {
            $failedTransactionId = 'failed_' . Str::uuid();
            
            Payment::create([
                'job_id' => $bid->job_id,
                'client_id' => $bid->job->client_id,
                'technician_id' => $bid->technician_id,
                'amount' => $bid->bid_amount,
                'payment_method' => 'credit_card',
                'payment_status' => 'failed',
                'transaction_id' => $failedTransactionId,
                'payment_date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function stripePost(Request $request): RedirectResponse
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $charge = Stripe\Charge::create([
                "amount" => $request->amount * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Job payment"
            ]);

            Payment::create([
                'job_id' => $request->job_id,
                'client_id' => auth()->id(),
                'technician_id' => $request->technician_id,
                'amount' => $request->amount,
                'payment_method' => 'credit_card',
                'payment_status' => 'completed',
                'transaction_id' => $charge->id,
                'payment_date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return back()->with('success', 'Payment successful!');

        } catch (\Exception $e) {
            $failedTransactionId = 'failed_' . Str::uuid();
            
            Payment::create([
                'job_id' => $request->job_id,
                'client_id' => auth()->id(),
                'technician_id' => $request->technician_id,
                'amount' => $request->amount,
                'payment_method' => 'credit_card',
                'payment_status' => 'failed',
                'transaction_id' => $failedTransactionId,
                'payment_date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }
}