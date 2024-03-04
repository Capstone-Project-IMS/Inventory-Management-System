<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckUserUpdatePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    //* Check if the current user is an admin or manager and is trying to update an employee or the current user is updating themselves
    public function handle(Request $request, Closure $next): Response
    {
        // Current user
        $currentUser = Auth::user();
        // If there is a user in the route then it is a manager or admin trying to update an employee
        $userToUpdate = $request->route('user') ?? $currentUser;

        // If the current user is not the user to update and the current user is a manager or admin and the user to update is not an employee
        if ($currentUser->id !== $userToUpdate->id && ($currentUser->hasRole(['admin', 'management'])) && !$userToUpdate->isEmployee()) {
            return response()->json(['error' => 'Managers and admins can only update employee users.'], 403);
        }
        return $next($request);
    }
}
