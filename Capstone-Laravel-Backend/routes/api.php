<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpControllers\EmployeeController;
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

// Customer registration
Route::post('/register', [AuthController::class, 'register'])->name('customer.register');

// Link thats in verification email when registering a user has to be named verification.verify
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');

// IF user verifies email they are able to login
Route::post('/login', [AuthController::class, 'login'])->name('app.login');

// Sends forgot password email
Route::post('/password/email', [AuthController::class, 'forgotPassword'])->name('password.email');

// Link thats in reset password email
// TODO: Will probably have to handle this in Flutter
Route::get('/password/reset/{token}', [AuthController::class, 'resetPasswordForm'])->name('password.form');

// Resets password
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');


// All routes below this line require a valid token
Route::middleware('auth:sanctum')->group(function () {
    // Get the user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

Route::post('/prodSearch', [EmployeeController::class, 'productSearch']);

    // Employee registration only accessible by managers
    Route::post('/register/employee', [AuthController::class, 'registerEmployee'])->name('employee.register');
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('app.logout');
});


