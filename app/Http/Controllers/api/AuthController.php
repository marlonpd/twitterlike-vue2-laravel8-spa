<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    /**
     * Return the user's access token.
     */
    public function authenticate(LoginRequest $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'success' => true,
            'user'  => $user,
            'token' => $jwt_token,
        ]);
    }

    public function logout() 
    {
        JWTAuth::logout();
        
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function register(RegisterRequest $request) 
    {

        $payload = [
            'name' => $request->name,
            'email' => $request->email,
            'password'  => bcrypt($request->password),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $user = User::create($payload);

        if ($user) {
            return response()->json(['user' => $user]);
        } else {
            return response()->json(['meserrorsage' => 'Encountered an error'], Response::HTTP_BAD_REQUEST);
        }
    }

     /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}