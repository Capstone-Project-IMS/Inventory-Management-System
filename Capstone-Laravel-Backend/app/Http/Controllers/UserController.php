<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Action;
use App\Models\Log;


class UserController extends Controller
{

    // Helper function to create a user with basic user information
    public function createUser(Request $request, $userTypeId)
    {
        $request->validate(
            [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => [
                    'required',
                    'string',
                    // must be at least 8 characters
                    'min:8',
                    // must contain at least one lowercase letter            
                    'regex:/[a-z]/',
                    // must contain at least one uppercase letter     
                    'regex:/[A-Z]/',
                    // must contain at least one digit     
                    'regex:/[0-9]/',
                    // must contain a special character     
                    'regex:/[@$!%*#?&]/',
                ],
                'confirm_password' => 'required|same:password',
            ],
            // Custom error messages for each input error
            [
                'first_name.required' => 'Please Enter First Name',
                'last_name.required' => 'Please Enter Last Name',
                'email.required' => 'Please Enter Email',
                'email.email' => 'Email is invalid',
                'email.unique' => 'Email is already taken',
                'password.required' => 'Please Enter Password',
                'password.min' => 'Password must be at least 8 characters',
                'password.regex' => 'Password must contain at least one lowercase letter, one uppercase letter, one digit, and one special character',
                'confirm_password.required' => 'Confirm password is required',
                'confirm_password.same' => 'Passwords does not match',
            ]
        );

        // Create basic user with whatever user type is passed
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
            'user_type_id' => $userTypeId,
        ]);
        // get the user that was just created
        $user = User::findByEmail($request->email);
        // Create log for registration
        $registerAction = Action::getByName('register');
        Log::create([
            'user_id' => $user->id,
            'action_id' => $registerAction->id,
        ]);

        // return the user to use to create employee or customer
        return $user;
    }
    // gets user info and relations depending on user_type
    public function dashboard()
    {
        // return json response with each user_types relations
        if (auth()->user()) {
            $user = auth()->user();
            $userDetails = $user->loadAllRelations();
            return response()->json($userDetails);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
