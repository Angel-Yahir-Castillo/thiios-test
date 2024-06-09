<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //data validate
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);

        //get credentials
        $credentials = $request->only('email', 'password');

        //attempt to login
        if (! $token = auth()->attempt($credentials)) {
            return jsonResponse(status: 401, message: 'Unauthorized');
        }

        //response with jwt
        return $this->respondWithToken($token);
    }

    public function register(CreateUserRequest $request){
        //create a user with the validated data
        $user = User::create($request->validated());

        //return the user data
        return jsonResponse(data: ['user' => UserResource::make($user)]);
    }

    public function me()
    {
        return jsonResponse(data: ['user' => UserResource::make(auth()->user())]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function logout()
    {
        //logout the user
        auth()->logout();

        return jsonResponse(message: 'Successfully logged out');
    }

    protected function respondWithToken($token)
    {
        return jsonResponse(data: [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
