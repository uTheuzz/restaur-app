<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected $authService;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {

            $credentials = request(['email', 'password']);

            if (!JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Unauthorized', 'status' => 'error'], 401);
            }

            $result = $this->authService->generateTokens($request);

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {

            auth()->logout();

            $result = $this->authService->invalidateTokens($request);

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
