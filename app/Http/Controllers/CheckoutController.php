<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Import the Log facade

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = session('cart', []);

        if (count($cartItems) === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // --- PREPARE AND SAVE THE ORDER BEFORE PAYMENT ---
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . uniqid(),
                'total_amount' => $total,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            foreach ($cartItems as $rowId => $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'custom_form_data' => $item['custom_form_data'],
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Creation Failed: ' . $e->getMessage()); // Log the actual error
            return redirect()->route('cart.index')->with('error', 'Failed to create order. Please try again.');
        }

        // --- MIDTRANS INTEGRATION ---
        // Add a check to ensure the Midtrans Snap class exists
        if (!class_exists('\Midtrans\Snap')) {
            return redirect()->route('cart.index')->with('error', 'Midtrans library not loaded. Please run "composer dump-autoload".');
        }

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
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $order->snap_token = $snapToken;
            $order->save();
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage()); // Log the actual error
            return redirect()->route('cart.index')->with('error', 'Payment gateway error. Please check the logs.');
        }

        session()->forget('cart');

        return view('checkout.index', compact('snapToken', 'order'));
    }
}