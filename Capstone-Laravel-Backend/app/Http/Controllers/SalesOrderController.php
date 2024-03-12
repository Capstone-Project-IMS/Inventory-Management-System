<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductDetail;

class SalesOrderController extends Controller
{
    // create SalesOrder if customer
    // update status of SalesOrder if manager or customer (pending, shipped, delivered, cancelled)
    // delete SalesOrder if customer or manager
    // Get all SalesOrders if manager
    // Get all SalesOrders for a specific Customer
    // Get SalesOrderDetails for a specific SalesOrder
    //

    //* Get all Sales Orders
    //* response: 200 Sales Orders Retrieved, 401 Unauthorized
    public function index()
    {
        $orders = SalesOrder::with('customer', 'customer.user', 'employee', 'employee.user', 'salesOrderDetails', 'salesOrderDetails.productDetail', 'salesOrderDetails.productDetail.product')->get();
        return $this->successResponse('Sales Orders retrieved successfully', $orders);
    }

    //* Get individual Sales Order
    //* response: 200 Sales Order Retrieved, 404 Sales Order Not Found, 401 Unauthorized
    public function show($id)
    {
        $order = SalesOrder::find($id);
        $orderRelations = $order->loadAllRelations();
        return $this->successResponse('Sales Order retrieved successfully', $orderRelations);
    }

    //* Purchase online cart
    //* response: 200 Sales Order Created, 400 Cart is Empty, 500 Failed to create order,401 Unauthorized
    public function orderOnline()
    {
        $currentCustomer = Auth::user()->customer;
        $cartItems = $currentCustomer->cartItems()->with('productDetail')->get();
        if ($cartItems->isEmpty()) {
            return $this->errorResponse('Cart is Empty', 400);
        }
        try {
            $salesOrder = $currentCustomer->orderOnline($cartItems);
            return $this->successResponse('Sales Order Created', ['order' => $salesOrder, 'details' => $salesOrder->salesOrderDetails]);
        }
        // Catch exception thrown from $currentCustomer->orderOnline and return json error
        catch (\Exception $e) {
            return $this->errorResponse('Failed to create order: ' . $e->getMessage(), 500);
        }
    }

    //TODO: Fulfill Sales Order item
    public function fulfillSalesOrderItem(Request $request, $id, $barcode)
    {
        // Get scanned barcode from flutter
        $productDetail = ProductDetail::where('barcode', $barcode)->first();
        // Find the sales order
        $salesOrder = SalesOrder::find($id);
        // Check if the product detail is in the sales order and belongs to the same customer
        $salesOrderDetail = $salesOrder->salesOrderDetails()->where('product_details_id', $productDetail->id)->first();
        if ($salesOrderDetail == null) {
            return $this->errorResponse('Product not in order', 400);
        }
        $salesOrderDetail = $salesOrder->salesOrderDetails()->find($request->sales_order_detail_id);
        $salesOrderDetail->fulfill($request->quantity);
        return $this->successResponse('Sales Order Item Fulfilled', $salesOrderDetail);
    }

    
}
