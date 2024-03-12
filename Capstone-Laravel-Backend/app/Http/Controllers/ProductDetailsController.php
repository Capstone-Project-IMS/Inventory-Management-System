<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductDetailsController extends Controller
{
    /**
     * Get all product details for a specific product
     */
    public function index(string $productId)
    {
        //
    }


    //? Same thing with products, do we create as vendor then put in purchase order or do we create as manager and only put in purchase order for more quantity?
    //? Is the item already in the system when the receiving department scans as received?
    public function store(Request $request)
    {
        //
    }

    /**
     * get this specific product detail
     */
    //* response: 200 Product Retrieved Successfully, 404 Product Not Found
    public function show(string $id)
    {
        $product = ProductDetail::find($id);
        $productRelations = $product->loadAllRelations();
        return $this->successResponse('Product Retrieved Successfully', $productRelations);
    }

    /**
     * Update a specific product detail
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Delete a product detail
     */
    public function destroy(string $id)
    {
        //
    }
}
