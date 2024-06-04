<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register', 'login']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',//|confirmed',
        ]);

        if ( $validator->fails() ) {
            return $this->sendError('Bad Request', errors: $validator->errors(), status: Response::HTTP_BAD_REQUEST);
        }

        $user = User::create([
            'uuid' => Str::uuid()->toString(),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username' => $request->username,
        ]);
        
        $user = $user->only(['uuid', 'email', 'username', 'created_at']);

        return $this->sendResponse(message: 'User created successfully!', extra: [ 'user' => $user ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',//|unique:users',
            'password' => 'required|string|min:6',//|confirmed',
        ]);

        if ( $validator->fails() ) {
            return $this->sendError('Bad Request', errors: $validator->errors(), status: Response::HTTP_BAD_REQUEST);
        }

        if ( !Auth::attempt($request->only('email', 'password')) ) {
            return $this->sendError('Unauthorized', 'Account not found!', Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $token = Auth::claims([])->fromUser($user);

        return $this->sendResponse(message: 'Successfully logged in!', extra: [
            'user' => [
                'uuid' => $user->uuid,
                'username' => $user->username,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);
    }

    public function refresh(Request $request)
    {
        $user = Auth::user();
        return $this->sendResponse(message: 'Successfully refresh token!', extra: [
            'user' => [
                'uuid' => $user->uuid,
                'username' => $user->username,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return $this->sendResponse(message: 'Successfully logged out!');
    }
}
