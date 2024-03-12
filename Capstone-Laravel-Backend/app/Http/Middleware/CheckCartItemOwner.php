<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

class CheckCartItemOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cartItemId = $request->route('id'); 
        $cartItem = CartItem::find($cartItemId);

        // Check if the cart item exists
        if (!$cartItem) {
            return response()->json(['error' => 'Item Not Found in Your Cart'], 404);
        }

        // Check if the user is the owner of the cart item
        $currentCustomer = Auth::user()->customer;
        if ($cartItem->customer_id !== $currentCustomer->id) {
            return response()->json(['error' => 'You Do Not have Permission to Edit This Item'], 403);
        }
        return $next($request);
    }
}
