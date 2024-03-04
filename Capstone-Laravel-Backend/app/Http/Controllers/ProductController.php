<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

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
    public function index()
    {
        $products = Product::with('vendor', 'productDetails', 'productDetails.productStorages', 'productDetails.floorLocation', 'productDetails.priority')->get();
        return $this->successResponse('Products Retrieved Successfully', $products);
    }


    //? Are we going to add products as a vendor or as a manager or as receiving?
    //? If we add products as a vendor, we can add products through purchase orders
    //? If we add products as a manager, we can add products as a form
    //? If we add products as receiving, we can add products through purchase orders after status is received
    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        $product = Product::find($id);
        $productRelations = $product->loadAllRelations();
        return $this->successResponse('Product Retrieved Successfully', $productRelations);
    }

    // Update product details name,description,category,img as vendor
    public function update(Request $request, string $id)
    {
        $currentUser = Auth::user();
        $product = Product::find($id);
        if ($currentUser->hasRole('vendor') && $product->vendor_id === $currentUser->vendor->id) {
            // update product details
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'category' => 'required|string',
                'display_image' => 'required|string',
            ]);

            $data = array_filter($request->only('name', 'description', 'category', 'display_image'), function ($value) {
                return null !== $value;
            });

            $product->update($data);
            return response()->json(['message' => 'Product Updated Successfully', 'product' => $product]);

        } 

        return response()->json(['error' => 'You Did Not Create This Product'], 401);
    }

    // audit product
    public function audit(string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
