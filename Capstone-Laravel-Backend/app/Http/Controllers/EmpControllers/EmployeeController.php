<?php

namespace App\Http\Controllers\EmpControllers;
use App\Http\Controllers\Controller;
use App\Models\ProductDetail;
use App\Models\Product;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function productSearch(Request $request) {
        $search = $request->get('search');
        $products = Product::where('name', 'like', '%'.$search.'%')->with('productDetails',)->get();
        dd($products[0]->productDetails[0]);

        /*
        Search  product name
                product id
                product vendor name

        */
    }
}
