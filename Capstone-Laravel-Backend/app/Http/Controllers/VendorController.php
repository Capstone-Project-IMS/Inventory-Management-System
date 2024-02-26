<?php

namespace App\Http\Controllers;

use App\Models\VendorContact;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{

    // So we can use the createUser function from the UserController
    protected $userController;

    public function __construct(UserController $userController)
    {
        $this->userController = $userController;
    }

    // Creating a vendor
    public function register(Request $request)
    {
        // Creating a vendor as Primary Contact where company name is unique
        $request->validate(['name' => 'required|string|unique:vendors'],['name.required' => 'Please Enter Vendor Name', 'name.unique' => 'Company Name Already Exists']);
        // Use the createUser function to use input to create a user with contact info of primary contact
        $userOrResponse = $this->userController->createUser($request, 3);
        if ($userOrResponse instanceof JsonResponse) {
            return $userOrResponse;
        } else {
            $user = $userOrResponse;
        }

        Vendor::create([
            'name' => $request->name,
        ]);
        $vendor = Vendor::where('name', $request->name)->first();
        VendorContact::create([
            'user_id' => $user->id,
            'vendor_id' => $vendor->id,
            'is_primary' => true,
        ]);


        $user->sendEmailVerificationNotification();

        // Return a json success message and the user that was created
        return $this->successResponse('User Created Successfully', $user);

    }

    // add a non-primary contact to a vendor, must be a primary contact to add a non-primary contact
    public function addContact(Request $request){
        $currentUser = Auth::user();
        if($currentUser && $currentUser->role == 'vendor' && $currentUser->vendorContact->is_primary){
            $userOrResponse = $this->userController->createUser($request, 3);
            if ($userOrResponse instanceof JsonResponse) {
                return $userOrResponse;
            } else {
                $user = $userOrResponse;
            }
            VendorContact::create([
                'user_id' => $user->id,
                'vendor_id' => $currentUser->vendorContact->vendor_id,
                'is_primary' => $request->is_primary,
            ]);

            $user->sendEmailVerificationNotification();
            return $this->successResponse('User Created Successfully', $user);
        } else {
            return $this->errorResponse('Unauthorized', 401);
        }
    }
}
