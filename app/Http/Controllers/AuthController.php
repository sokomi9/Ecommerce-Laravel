<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * create a new AuthCntroller instance
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
    }
    /**
     * Get a JWT via given credentials
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    public function signup(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400); //Bad request client error
        }
        $user = User::create(array_merge(
            $validator->validated(), ['password'=>bcrypt($request->password)]
        ));
        return response()->json([
            'message'=> 'User successfully registered',
            'user' => $user
        ], 201);//New resource created on server
    }
    /**
     * Log the user out(Invalidate the token)
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully logged out']);
    }
    /**
     * Refresh a token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
       return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile(){
        return response()->json(auth()->user());
    }
    /**
     * Get the token array structure
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        $user = auth()->user();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
