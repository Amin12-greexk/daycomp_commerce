<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index()
    {
        // Retrieve the cart items from the session. If null, use an empty array.
        $cartItems = session('cart', []);
        $total = 0;

        // Calculate the total price
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        // Validate the incoming request data
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'custom_fields' => 'sometimes|array' // Ensure custom_fields is an array if it exists
        ]);

        // Get the cart from the session, or initialize an empty array
        $cart = session()->get('cart', []);

        // This creates a unique ID for each line item in the cart.
        // This is crucial because a user might add the same product twice
        // with different custom options (e.g., two shirts with different text).
        $rowId = Str::random(40);

        $customFieldsData = $request->input('custom_fields', []);
        $customFieldsFiles = $request->file('custom_fields', []);

        // Process file uploads from the custom form
        foreach ($customFieldsFiles as $fieldName => $file) {
            $path = $file->store('custom_uploads', 'public');
            // Store the file path in our data array
            $customFieldsData[$fieldName] = $path;
        }

        // Add the new item to the cart array
        $cart[$rowId] = [
            "product_id" => $product->id,
            "name" => $product->name,
            "quantity" => $request->quantity,
            "price" => $product->price,
            "image" => $product->image,
            "custom_form_data" => $customFieldsData, // Store the filled custom form data
            "custom_form_schema" => json_decode($product->custom_form_schema, true) // Store the schema for later use
        ];

        // Save the updated cart back to the session
        session()->put('cart', $cart);

        // Redirect back to the previous page with a success message
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, $rowId)
    {
        $cart = session()->get('cart', []);

        // Check if the item exists and the quantity is valid
        if (isset($cart[$rowId]) && $request->quantity > 0) {
            $cart[$rowId]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
        }

        return redirect()->route('cart.index')->with('error', 'Invalid item or quantity.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove($rowId)
    {
        $cart = session()->get('cart', []);

        // Check if the item exists in the cart
        if (isset($cart[$rowId])) {
            // Remove the item from the cart array
            unset($cart[$rowId]);
            // Save the updated cart back to the session
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Product removed from cart successfully.');
    }
}