<?php

namespace App\Http\Middleware;

use App\Exceptions\MultipleUserLoginException;
use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthMiddleware
{
    protected $authService;

    protected $internalToken;

    protected $externalToken;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $this->externalToken = $this->authService->getExternalToken($request); 
            if (Cache::has($this->externalToken)) {
                $this->internalToken = Cache::get($this->externalToken);
                $this->authService->checkTokens($request, $this->internalToken, $this->externalToken);
                $request->headers->set('Authorization', 'Bearer '.$this->internalToken['token']);
            } else {
                return response()->json(['message' => 'Invalid or expired token!', 'status' => 'error'], 401);
            }
           JWTAuth::parseToken()->authenticate(); 
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                try {
                    $refreshed = JWTAuth::refresh(JWTAuth::getToken());
                    JWTAuth::setToken($refreshed)->toUser();
                    $request->headers->set('Authorization','Bearer '.$refreshed);
                    $this->authService->updateTokens($this->externalToken, $this->internalToken, $refreshed);
                } catch (\Exception $e) {
                    return response()->json(['message' => 'Token cannot be refreshed, please Login again', 'status' => 'error'], 401);
                }
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['message' => 'Invalid token!', 'status' => 'error'], 401);
            } else if ($e instanceof MultipleUserLoginException) {
                return response()->json(['message' => $e->getMessage(), 'status' => 'error'], 401);
            } else {
                return response()->json(['message' => 'Authorization token not found!', 'status' => 'error'], 401);
            }
        }
        
        return $next($request);
    }
}
