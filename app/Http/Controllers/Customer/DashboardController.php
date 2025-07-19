<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the customer's dashboard, including order history.
     */
    public function index()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Fetch the user's orders, with their details and products, ordered by the newest first
        $orders = $user->orders()->with('details.product')->latest()->paginate(10);

        // Pass the orders to the view
        return view('customer.dashboard', compact('orders'));
    }
}