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
    }

    // Get all employees
    public function index()
    {
        $employees = Employee::with('user', 'purchaseOrders', 'salesOrders', 'employeeType', 'user.logs')->get();
        return $this->successResponse('Employees retrieved successfully', $employees);
    }

    // Get single employee, pass the id when "clicking" on an employee
    public function show($id)
    {
        $employee = Employee::with('user')->find($id);
        $employeeRelations = $employee->loadAllRelations();
        return $this->successResponse('Employee retrieved successfully', $employeeRelations);
    }

    // Update employee 
    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_type_id' => 'required|integer',
            'hourly_rate' => 'required|numeric|min:10.00',
        ]);
        $employee = Employee::find($id);
        $employee->update([
            'employee_type_id' => $request->employee_type_id,
            'hourly_rate' => $request->hourly_rate,
        ]);
        return $this->successResponse('Employee updated successfully', $employee);
    }

    // Delete employee as manager
    public function destroy($id)
    {
        $employee = Employee::find($id);
        $employee->user->delete();
        return $this->successResponse('Employee deleted successfully', $employee);
    }

    // Clock in/out, uses signed in user
    public function clock()
    {
        $employee = Auth::user()->employee;
        $log = $employee->clockIn();
        $message = $log->action->action === 'clock in' ? 'Clocked in successfully' : 'Clocked out successfully';
        return $this->successResponse($message, $log);
    }

}
