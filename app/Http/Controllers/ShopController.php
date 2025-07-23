<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display the main shop page with all products.
     */
    public function index(Request $request)
    {
        // Start with a base query for products
        $productQuery = Product::query();

        // Check if a category filter is present in the request URL
        if ($request->has('category')) {
            $categorySlug = $request->query('category');
            // Add a condition to the query to filter by the category's slug
            $productQuery->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            });
        }

        // Fetch the filtered (or unfiltered) products
        $products = $productQuery->latest()->paginate(12);

        // Return the shop index view, passing the products to it
        return view('shop.index', compact('products'));
    }

    /**
     * Display a single product page.
     */
    public function show(Product $product)
    {
        // The 'product' is automatically fetched by Laravel's route model binding.
        $formSchema = json_decode($product->custom_form_schema, true);

        // --- START NEW CODE ---

        // Get products from the same category, excluding the current product
        $relatedProducts = Product::where('category_id', $product->category_id) // Filter by the current product's category ID
            ->where('id', '!=', $product->id) // Exclude the current product itself
            ->inRandomOrder() // Optional: display in a random order
            ->limit(4) // Limit to 4 related products (you can adjust this)
            ->get();

        // --- END NEW CODE ---

        // Return the single product view, passing the product, formSchema, and relatedProducts
        return view('shop.show', compact('product', 'formSchema', 'relatedProducts'));
    }
}
