<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($this->auth->guard($guard)->guest()) {
            if ($request->has('api_token')) {
                $token = $request->input('api_token');
                $check_token = User::whereApiToken($token)->first();
                if ($check_token == null) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Permission not allowed'
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Login please!'
                ]);
            }
        }
        return $next($request);
    }
}
