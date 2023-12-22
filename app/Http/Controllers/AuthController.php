<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
    
        $credentials = request(['email','password']);
        if (!Auth::attempt($credentials))
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;
    
        return response()->json([
            'message' => 'Authorised',
            'payload' => [
                'accessToken' => $token,
                'username' => $user->username,
                'id' => $user->id    
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
        'message' => 'Successfully logged out'
        ]);
    
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email'=>'required|string|unique:users',
            'password'=>'required|string'
        ]);

        $user = new User([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($user->save())
        {
            // $tokenResult = $user->createToken('Personal Access Token');
            // $token = $tokenResult->plainTextToken;

            return response()->json([
                'message' => 'Successfully created user!'
            ], 201);

        }
        else
        {
            return response()->json(['message' => 'Provide proper details']);
        }
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
