<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User; // Pastikan model User sudah diimport

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil data analitik
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();

        // Mengambil total pelanggan berdasarkan kolom 'role'
        // Jika 'role' diatur ke 'customer' untuk pelanggan
        $totalCustomers = User::where('role', 'customer')->count();

        // Meneruskan data ke view
        return view('admin.dashboard', compact('totalProducts', 'totalCategories', 'totalOrders', 'totalCustomers'));
    }
}
