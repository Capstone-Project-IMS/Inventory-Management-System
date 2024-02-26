<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Customer;
use App\Models\EmployeeType;
use App\Models\Log;
use App\Models\User;
use App\Models\UserType;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;



class AuthController extends Controller
{
    // So we can use the createUser function from the UserController
    protected $userController;

    public function __construct(UserController $userController)
    {
        $this->userController = $userController;
    }

    // login
    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string',
            ],
            // Custom error messages for each input error
            [
                'email.required' => 'Please Enter Email',
                'email.email' => 'Email is invalid',
                'password.required' => 'Please Enter Password',
            ]
        );

        // Auth attempt to login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // if email is verified
            if (Auth::user()->hasVerifiedEmail()) {
                // Create log for login
                $loginAction = Action::getByName('login');
                Log::create([
                    'user_id' => Auth::user()->id,
                    'action_id' => $loginAction->id,
                ]);
                // create a token for the user
                $token = Auth::user()->createToken('user_token')->plainTextToken;
                // return the user and the token
                return $this->successResponse('Login Successful', [
                    'success' => true,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ], 200);
            }
            // if email is not verified
            else {
                return $this->errorResponse('Email not verified', 401);
            }
        }
        // if login fails
        else {
            return $this->errorResponse('Invalid credentials', 401);
        }
    }


    // Logout. Deletes the token of logged in user from the database
    public function logout(Request $request)
    {
        // Create log for logout
        $logoutAction = Action::getByName('logout');
        Log::create([
            'user_id' => $request->user()->id,
            'action_id' => $logoutAction->id,
        ]);
        // Deletes the token from the database
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse('Logged out successfully');
    }

    // method that runs when the user clicks the link in the email
    public function verify(Request $request)
    {
        // Find the user with the id in the request
        $user = User::find($request->id);
        // if the user has already verified their email
        if ($user->hasVerifiedEmail()) {
            // return email already verified message
            return $this->successResponse('Email already verified');
        }
        // if the user has not verified their email
        if ($user->markEmailAsVerified()) {
            // Create log for email verification
            $verifyAction = Action::getByName('verify');
            Log::create([
                'user_id' => $user->id,
                'action_id' => $verifyAction->id,
            ]);

            // return email verified message
            return $this->successResponse('Email verified successfully');
        }
        return $this->errorResponse('Email could not be verified');
    }


    // Sends forgot password email
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email'], [
            'email.required' => 'Please Enter Email',
            'email.email' => 'Email is invalid',
            'email.exists' => 'Email does not exist'
        ]);

        $status = Password::sendResetLink(
            ['email' => $request->email,]
        );

        $token = DB::table('password_reset_tokens')->where('email', $request->email)->value('token');

        return $status === Password::RESET_LINK_SENT
            ? $this->successResponse('Email sent successfully', ['token' => $token])
            : $this->errorResponse('Email could not be sent');
    }

    // Resets password
    public function resetPassword(Request $request)
    {
        $request->validate(
            [
                'token' => 'required',
                'email' => 'required|email',
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
                'password_confirmation' => 'required|same:password',
            ],
            // Custom error messages for each input error
            [
                'token.required' => 'Token is required',
                'email.required' => 'Email is required',
                'email.email' => 'Email is invalid',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 8 characters',
                'password.regex' => 'Password must contain at least one lowercase letter, one uppercase letter, one digit, and one special character',
                'password_confirmation.required' => 'Confirm password is required',
                'password_confirmation.same' => 'Passwords does not match',
            ]
        );

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password
                ])->save();
            }
        );
        return $status == Password::PASSWORD_RESET
            ? $this->successResponse('Password reset successfully')
            : $this->errorResponse('Password could not be reset');
    }

    // Link thats in reset password email
    public function resetPasswordForm(Request $request)
    {
        $testEmail = DB::table('password_reset_tokens')->where('token', $request->token)->value('email');
        $testUser = User::findByEmail($testEmail);
        return $this->successResponse('Reset password form', ['token' => $request->token, 'user' => $testUser]);
    }

}
