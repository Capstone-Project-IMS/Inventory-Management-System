<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // All controllers inherit from this class

    // Method to get models with their relations
    public function modelWithRelations($model, $relations)
    {
        return $model::with($relations)->get();
    }

    // Methods to return success and error responses
    // 100s: Informational responses
    // 200s: Successful responses
    // 300s: Redirection responses
    // 400s: Client error responses
    // 500s: Server error responses
    public function successResponse($message, $data = null, $code = 200)
    {
        if ($data == null) {
            return response()->json(['message' => $message], $code);
        } else {
            return response()->json(['message' => $message, 'data' => $data], $code);
        }
    }

    public function errorResponse($message, $code = 400)
    {
        return response()->json(['message' => $message], $code);
    }
}
