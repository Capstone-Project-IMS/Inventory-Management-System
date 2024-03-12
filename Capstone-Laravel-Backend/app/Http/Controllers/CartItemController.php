<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{


    // Get all cart items for the current customer
    public function index()
    {
        $currentCustomer = Auth::user()->customer;
        // dd(Auth::user()->customer->cartItems);
        $cartItems = $currentCustomer->cartItems()->with('productDetail', 'productDetail.product')->get();
        return $this->successResponse('Cart Items', $cartItems);
    }

    // Get all saved cart items for the current customer
    public function saved()
    {
        $currentCustomer = Auth::user()->customer;
        // dd($currentCustomer->cartItems);
        $savedCartItems = $currentCustomer->savedCartItems();
        return $this->successResponse('Saved Cart Items', $savedCartItems);
    }

    // Add to online cart
    public function addToOnlineCart(Request $request, string $id)
    {
        $productDetail = ProductDetail::find($id);
        $quantity = $request->quantity;
        // Check if the product detail exists
        if (!$productDetail->isAvailable($quantity)) {
            return $this->errorResponse('Not Enough Product in Stock: ' . $productDetail->storage, 400);
        }
        $currentCustomer = Auth::user()->customer;
        // check if the product is already in the cart
        if ($currentCustomer->hasInCart($productDetail)) {
            return $this->errorResponse('Product Already in Cart', 400);
        }
        $cartItem = $currentCustomer->addToOnlineCart($productDetail, $quantity);
        // Create log for adding to cart
        $log = $currentCustomer->createLog('Added to Cart', $cartItem);
        return $this->successResponse('Product Added to Cart', $cartItem);
    }

    // Save cart item
    public function toggleCartItemSave(string $id)
    {
        $cartItem = CartItem::find($id);
        // If the cart item is saved unsave it
        if ($cartItem->isSaved()) {
            $cartItem->unsaveCartItem();
            return $this->successResponse('Item Added Back to Cart', $cartItem);
        }
        // If the cart item is in cart save it
        else {
            $cartItem->saveCartItem();
            return $this->successResponse('Item Saved', $cartItem);
        }
    }

    // Update cart item quantity whether to add or remove
    //* Response: 200 Quantity Added, 400 Not Enough Product in Stock, 
    public function updateQuantity(Request $request, string $id)
    {
        $cartItem = CartItem::find($id);
        // Check if the quantity is being added or removed
        if ($cartItem->productDetail->isAvailable($request->quantity)) {
            $message = $request->quantity > $cartItem->quantity ? 'Added' : 'Removed';
            $cartItem->updateQuantity($request->quantity);
            return $this->successResponse('Quantity ' . $message, $cartItem);
        } else {
            // dd("Not enough in stock " . $cartItem->productDetail->storage . ", " . $request->quantity);
            return $this->errorResponse('Not Enough Product in Stock', 400);
        }
    }

    // Remove cart item
    //* Response: 200 Item Removed, 404 Not Found
    public function destroy(string $id)
    {
        $cartItem = CartItem::find($id);
        // Remove the cart item
        $cartItem->delete();
        return $this->successResponse('Item Removed', $cartItem);
    }
}
