<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
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

    // Available online products
    public function availableOnline()
    {

        $products = ProductDetail::with('product', 'productStorages', 'floorLocation', 'priority')->whereHas('productStorages', function ($query) {
            $query->where('quantity', '>', 0);
        })->get();
        return $this->successResponse('Available Products Retrieved Successfully', $products);
        // Gets all products "available online" which is products that have a storage greater than 0
    }

    // Filter Products dynamically
    public function filterProducts(Request $request)
    {
        $allProducts = Product::with('vendor', 'productDetails', 'productDetails.productStorages', 'productDetails.floorLocation', 'productDetails.priority');

        // Filter by category
        if ($request->has('category')) {
            $allProducts->where('category', $request->category);
        }

        // By color
        if ($request->has('color')) {
            $allProducts->whereHas('productDetails', function ($query) use ($request) {
                $query->where('color', $request->color);
            });
        }

        // By size
        if ($request->has('size')) {
            $allProducts->whereHas('productDetails', function ($query) use ($request) {
                $query->where('size', $request->size);
            });
        }

        // By vendor name
        if ($request->has('vendor')) {
            $allProducts->whereHas('vendor', function ($query) use ($request) {
                $query->where('name', $request->vendor);
            });
        }

        // By location name
        if ($request->has('location')) {
            $allProducts->whereHas('productDetails', function ($query) use ($request) {
                $query->whereHas('floorLocation', function ($query) use ($request) {
                    $query->whereHas('location', function ($query) use ($request) {
                        $query->where('name', $request->location);
                    });
                });
            });
        }

        // In price range
        if ($request->has('price_min') && $request->has('price_max')) {
            $low = $request->price_min;
            $high = $request->price_max;
            $allProducts->whereHas('productDetails', function ($query) use ($low, $high) {
                $query->whereBetween('price', [$low, $high]);
            });
        }

        // In store or online
        if ($request->has('purchase_location')) {
            if ($request->purchase_location === 'online') {
                $allProducts->whereHas('productDetails', function ($query) {
                    $query->whereHas('productStorages', function ($query) {
                        $query->where('quantity', '>', 0);
                    });
                });
            } else if ($request->purchase_location === 'in-store') {
                $allProducts->whereHas('productDetails', function ($query) {
                    $query->whereHas('floorLocation', function ($query) {
                        $query->where('current_capacity', '>', 0);
                    });
                });
            }
        }

        // All in stock/out of stock
        if ($request->has('stock')) {
            if ($request->stock === 'in-stock') {
                $allProducts->whereHas('productDetails', function ($query) {
                    $query->whereHas('productStorages', function ($query) {
                        $query->where('quantity', '>', 0);
                    });
                });
                $allProducts->whereHas('productDetails', function ($query) {
                    $query->whereHas('floorLocation', function ($query) {
                        $query->where('current_capacity', '>', 0);
                    });
                });
            } else if ($request->stock === 'out-of-stock') {
                $allProducts->whereHas('productDetails', function ($query) {
                    $query->whereHas('productStorages', function ($query) {
                        $query->where('quantity', 0);
                    });
                });
                $allProducts->whereHas('productDetails', function ($query) {
                    $query->whereHas('floorLocation', function ($query) {
                        $query->where('current_capacity', 0);
                    });
                });
            }
        }


        // Search by description, name,color, size, category
        if ($request->has('search')) {
            $search = $request->search;
            $allProducts->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('productDetails', function ($query) use ($search) {
                        $query
                            ->whereRaw("CONCAT(product.name, ' ', color, ' ', size) LIKE ?", ['%' . $search . '%'])
                            ->orWhere('color', 'like', '%' . $search . '%')
                            ->orWhere('size', 'like', '%' . $search . '%');
                    });
            });
        }


        $allProducts = $allProducts->get();
        return $this->successResponse('Filtered Products Retrieved Successfully', $allProducts);
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
        return $this->successResponse('Product Details Retrieved Successfully', $productRelations);
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
