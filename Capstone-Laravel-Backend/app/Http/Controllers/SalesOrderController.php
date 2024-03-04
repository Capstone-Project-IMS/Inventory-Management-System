<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;

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
    public function index()
    {
        $orders = SalesOrder::with('customer', 'customer.user', 'employee', 'employee.user', 'salesOrderDetails', 'salesOrderDetails.productDetail', 'salesOrderDetails.productDetail.product')->get();
        return $this->successResponse('Sales Orders retrieved successfully', $orders);
    }

    //* Get individual Sales Order
    public function show($id)
    {
        $order = SalesOrder::find($id);
        $orderRelations = $order->loadAllRelations();
        return $this->successResponse('Sales Order retrieved successfully', $orderRelations);
    }

}
