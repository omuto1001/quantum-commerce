<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    // Redirects the customer to Flutterwave's hosted checkout page.
    // Flutterwave's page collects card/mobile money details securely -
    // we never handle or store raw card numbers ourselves.
    public function initiate(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        $reference = 'QC-' . $order->id . '-' . Str::random(8);
        $order->update(['payment_reference' => $reference]);

        $response = Http::withToken(config('services.flutterwave.secret_key'))
            ->post('https://api.flutterwave.com/v3/payments', [
                'tx_ref' => $reference,
                'amount' => $order->total_amount,
                'currency' => 'UGX',
                'redirect_url' => route('payment.callback'),
                'customer' => [
                    'email' => Auth::user()->email,
                    'name' => Auth::user()->name,
                    'phonenumber' => Auth::user()->phone,
                ],
                'customizations' => [
                    'title' => 'Quantum Commerce',
                    'description' => 'Payment for Order #' . $order->id,
                ],
            ]);

        $data = $response->json();

        if (isset($data['status']) && $data['status'] === 'success') {
            return redirect($data['data']['link']);
        }

        // Log the real reason for failure so we can see it in storage/logs/laravel.log
        // instead of guessing. This stays in permanently - it's normal, correct
        // practice to log failed payment attempts for debugging and auditing.
        Log::error('Flutterwave payment initiation failed', [
            'order_id' => $order->id,
            'http_status' => $response->status(),
            'response_body' => $data,
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Could not start payment. Please try again or choose Cash on Delivery.');
    }

    // Flutterwave redirects the customer back here after payment attempt
    public function callback(\Illuminate\Http\Request $request)
    {
        $reference = $request->query('tx_ref');
        $status = $request->query('status');

        $order = Order::where('payment_reference', $reference)->first();

        if (! $order) {
            return redirect()->route('orders.index')->with('success', 'Payment reference not found.');
        }

        if ($status === 'successful') {
            $transactionId = $request->query('transaction_id');

            $verify = Http::withToken(config('services.flutterwave.secret_key'))
                ->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify");

            $verifyData = $verify->json();

            $verifiedAmount = $verifyData['data']['amount'] ?? 0;
            $verifiedStatus = $verifyData['data']['status'] ?? null;

            if ($verifiedStatus === 'successful' && (float) $verifiedAmount === (float) $order->total_amount) {
                $order->update(['payment_status' => 'paid']);
                return redirect()->route('orders.show', $order)->with('success', 'Payment successful! Your order is confirmed.');
            }

            Log::error('Flutterwave payment verification failed', [
                'order_id' => $order->id,
                'verify_response' => $verifyData,
            ]);
        }

        $order->update(['payment_status' => 'failed']);
        return redirect()->route('orders.show', $order)->with('success', 'Payment was not completed. You can try again.');
    }
}