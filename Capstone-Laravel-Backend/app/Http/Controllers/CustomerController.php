<?php

namespace App\Http\Controllers;

use App\Models\UserType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Customer;


class CustomerController extends Controller
{

    // update Customer
    // delete Customer
    // Get items in cart
    // checkout

    // So we can use the createUser function from the UserController
    protected $userController;

    public function __construct(UserController $userController)
    {
        $this->userController = $userController;
    }
    // Creating a customer
    public function register(Request $request)
    {
        $customerTypeId = UserType::where('role', 'customer')->first()->id;
        // create an instance of the userController and use the createUser function to create a user
        $userOrResponse = $this->userController->createUser($request, $customerTypeId);
        if ($userOrResponse instanceof JsonResponse) {
            return $userOrResponse;
        } else {
            $user = $userOrResponse;
        }
        Customer::create([
            'user_id' => $user->id,
        ]);

        $user->sendEmailVerificationNotification();

        // Return a json success message and the user that was created
        return $this->successResponse('User created successfully', $user);

    }
}
