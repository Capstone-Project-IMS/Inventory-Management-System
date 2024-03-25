<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Create product if manager
    // update product based on user type
    // delete product if manager
    // Get all products
    // Search for products
    // Filter products by category/price/sizes/colors/vendors
    // Scan product for information
    // 
    /**
     * Get all products
     */
    public function index()
    {
        //
    }

    /**
     * Create a new product
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Get information about specific product
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Post to update a specific product
     */
    public function update(Request $request, string $id)
    {
        //
    }

    // audit product
    public function audit(string $id)
    {
        //
    }


    /**
     * Delete a product
     */
    public function destroy(string $id)
    {
        //
    }
}
