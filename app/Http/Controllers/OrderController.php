<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Handle the successful payment callback from the client-side JavaScript.
     * This is used for development/testing without a webhook.
     */
    public function handlePaymentSuccess(Request $request)
    {
        // Get the JSON payload from the request
        $payload = $request->json()->all();

        // Find the order in the database
        $order = Order::where('order_number', $payload['order_id'])->first();

        if ($order) {
            // Update the order status based on the result
            $order->payment_status = 'paid';
            $order->status = 'processing';
            $order->midtrans_response = json_encode($payload); // Save the response
            $order->save();

            // Return a success response to the JavaScript
            return response()->json(['status' => 'success', 'message' => 'Payment successful and order updated.']);
        }

        // Return an error response if the order is not found
        return response()->json(['status' => 'error', 'message' => 'Order not found.'], 404);
    }


    /**
     * Handle Midtrans notification webhook (for production).
     */
    public function notificationHandler(Request $request)
    {
        // 1. Set Midtrans configuration
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        try {
            // 2. Create a notification object from the incoming request
            $notification = new \Midtrans\Notification();

            // 3. Extract key information from the notification
            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status;

            // Log the notification for debugging purposes
            Log::info("Midtrans Notification Received. Order ID: {$orderId}, Status: {$transactionStatus}");

            // 4. Find the corresponding order in your database
            $order = Order::where('order_number', $orderId)->first();

            if ($order) {
                // 5. Check the transaction status and update the order
                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    if ($fraudStatus == 'accept') {
                        $order->payment_status = 'paid';
                        $order->status = 'processing';
                    }
                } else if ($transactionStatus == 'pending') {
                    $order->payment_status = 'pending';
                } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                    $order->payment_status = 'failed';
                }

                $order->midtrans_response = json_encode($notification->getResponse());
                $order->save();
            }

            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            Log::error("Midtrans Notification Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function retryPayment(Order $order)
    {
        // Security: Ensure the authenticated user owns this order
        if (auth()->id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure the order is actually unpaid before proceeding
        if ($order->payment_status !== 'unpaid' && $order->payment_status !== 'failed') {
            return redirect()->route('customer.dashboard')->with('error', 'This order cannot be paid for again.');
        }

        // --- RE-GENERATE MIDTRANS TOKEN ---
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            // Save the new snap token to the order
            $order->snap_token = $snapToken;
            $order->save();

            // Redirect back to the dashboard and flash the new token to the session
            return redirect()->route('customer.dashboard')->with('snapToken', $snapToken);

        } catch (\Exception $e) {
            return redirect()->route('customer.dashboard')->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }
}