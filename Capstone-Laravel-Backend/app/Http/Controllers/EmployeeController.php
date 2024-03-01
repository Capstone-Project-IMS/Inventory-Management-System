<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\UserType;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    // Tasks all employees can do
    // We can make controller for each role
    protected $userController;

    public function __construct(UserController $userController)
    {
        $this->userController = $userController;
    }

    // Creating an employee
    public function register(Request $request)
    {
        $currentUser = Auth::user();
        if ($currentUser && $currentUser->hasRole(["admin", "manager"])) {
            $userType = UserType::where('role', 'employee')->first()->id;
            $userOrResponse = $this->userController->createUser($request, $userType);
            if (`$userOrResponse` instanceof JsonResponse) {
                return $userOrResponse;
            } else {
                $user = $userOrResponse;
            }
            Employee::create([
                'user_id' => $user->id,
                'employee_type_id' => $request->employee_type_id,
            ]);

            $user->sendEmailVerificationNotification();
            return $this->successResponse('User created successfully', $user);
        } else {
            return $this->errorResponse('Unauthorized', 401);
        }
    }
    
}
