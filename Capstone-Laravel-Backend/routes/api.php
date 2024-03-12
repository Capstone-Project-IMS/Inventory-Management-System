<?php

use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductDetailsController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//* Customer registration
Route::post('/register', [CustomerController::class, 'register'])->name('customer.register');

//* Vendor registration
Route::post('/register/vendor', [VendorController::class, 'register'])->name('vendor.register');

//* Link thats in verification email when registering a user has to be named verification.verify
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');

//* IF user verifies email they are able to login
Route::post('/login', [AuthController::class, 'login'])->name('app.login');

//* Sends forgot password email
Route::post('/password/email', [AuthController::class, 'forgotPassword'])->name('password.email');

//* Link thats in reset password email
// TODO: Will probably have to handle this in Flutter. Display a form to reset password with token in url or something
Route::get('/password/reset/{token}', [AuthController::class, 'resetPasswordForm'])->name('password.form');

//* Resets password
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');

//? Idk how to handle guests in flutter so if a user is not logged in they can still view products but ill put these here for now
//* View all products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
//* View individual product will use /products because /product will be used for product details
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');
//* Get specific product detail
Route::get('/product/{id}', [ProductDetailsController::class, 'show'])->name('product.show');
//! Available online products
// Route::get('/products/available', [ProductController::class, 'availableOnline'])->name('products.available');
//* Filter and search products
Route::get('/products/filter/search', [ProductController::class, 'filterProducts'])->name('products.filter');


//* All routes below this line require a valid token
Route::middleware('auth:sanctum')->group(function () {
    //* ALL USER ROUTES
    // Get the logged in user information with relationships
    Route::get('/user', [UserController::class, 'dashboard'])->name('app.user');
    // Edit user information uses user.update middleware to check if user is updating their own information or a manager is updating an employee
    Route::patch('/user', [UserController::class, 'update'])->middleware('user.update')->name('user.update');
    // Delete user
    Route::delete('/user', [UserController::class, 'destroy'])->name('user.destroy');
    //* END ALL USER ROUTES

    //* CUSTOMER ONLY ROUTES
    Route::middleware('role:admin,customer')->group(function () {
        // View Cart
        Route::get('/cart', [CartItemController::class, 'index'])->name('cart.index');
        // View Saved Cart
        Route::get('/cart/saved', [CartItemController::class, 'saved'])->name('cart.saved');
        // Add to cart pass the id of the product detail you want to add to cart
        Route::post('/cart/{id}', [CartItemController::class, 'addToOnlineCart'])->name('cart.add');
        // save cart item pass the id of the cart item you want to save
        Route::patch('/cart/save/{id}', [CartItemController::class, 'toggleCartItemSave'])->middleware('cart.item.owner')->name('cart.save');
        // Update cart item quantity pass the cart item id
        Route::patch('/cart/quantity/{id}', [CartItemController::class, 'updateQuantity'])->middleware('cart.item.owner')->name('cart.quantity');
        // Delete cart item pass the cart item id
        Route::delete('/cart/{id}', [CartItemController::class, 'destroy'])->middleware('cart.item.owner')->name('cart.destroy');

        //? Add checkout route to confirm purchase before removing inventory
        //? Route::post('/cart/checkout', [SalesOrderController::class, 'checkout'])->name('cart.checkout');
        // Purchase cart items adds all in-cart items to a sales order
        Route::post('/cart/online/order', [SalesOrderController::class, 'orderOnline'])->name('cart.order');


    });

    //* ADMIN ONLY ROUTES
    Route::middleware('role:admin')->group(function () {
        // Get all users as admin
        Route::get('/users', [UserController::class, 'index'])->middleware('role:admin')->name('users.index');
    });


    //* VENDOR ONLY ROUTES
    Route::middleware('role:vendor')->group(function () {
        // Vendor add contact only accessible by primary contacts
        Route::post('/vendor/addContact', [VendorController::class, 'addContact'])->name('vendor.add.contact');
    });


    //* ALL EMPLOYEE ROUTES
    Route::middleware('role:management,general,fulfillment,receiving')->group(function () {
        // Employee clock in/out
        Route::post('/employee/clock', [EmployeeController::class, 'clock'])->name('employee.clock');
    });

    //* FULFILLMENT EMPLOYEE ROUTES
    Route::middleware('role:fulfillment,management,admin')->group(function () {
        // Get all SalesOrders
        Route::get('/sales', [SalesOrderController::class, 'index'])->name('sales.orders.index');
        // Get individual SalesOrder
        Route::get('/sales/{id}', [SalesOrderController::class, 'show'])->name('sales.order.show');
        // Fulfill SalesOrderDetails
        Route::patch('/sales/fulfill/{id}/{barcode)', [SalesOrderController::class, 'fulfill'])->name('sales.fulfill');
    });

    //* MANAGER ONLY ROUTES
    Route::middleware('role:management')->group(function () {
        // Employee registration
        Route::post('/register/employee', [EmployeeController::class, 'register'])->name('employee.register');

        // Get all employees
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');

        // Get individual employee
        Route::get('/employee/{id}', [EmployeeController::class, 'show'])->name('employee.show');

        // Update employee employee type or hourly rate
        Route::put('/employee/{id}', [EmployeeController::class, 'update'])->name('employee.update');

        // Edit employee user information pass the user id of the employee you want to edit
        Route::patch('/employee/{user}/user', [UserController::class, 'update'])->middleware('user.update')->name('employee.user.update');

        // Delete employee
        Route::delete('/employee/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');

        // Get all SalesOrders
        Route::get('/sales', [SalesOrderController::class, 'index'])->name('sales.orders.index');

        // Get individual SalesOrder
        Route::get('/sales/{id}', [SalesOrderController::class, 'show'])->name('sales.order.show');

    });


    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('app.logout');
});


