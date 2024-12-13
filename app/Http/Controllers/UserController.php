<?php

namespace App\Http\Controllers;

use App\DTOs\UserDTOIN;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    private UserService $service;
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function authenticate(Request $request)
    {
        $validationError = UserRequest::validateUser(...$request->all());

        if ($validationError) {
            return response()->json(['error' => $validationError], Response::HTTP_BAD_REQUEST);
        }

        $userDTOIN = new UserDTOIN($request->name, $request->email,$request->password);

        $user = $this->service->getUser($userDTOIN);

        if (!Hash::check($request->password, $user[0]->password)) {
            return response()->json(['error' => 'Password is incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        $payload = [
            'iss' => env('JWT_ISSUER'), // Issuer
            'sub' => $user[0]->id, // Subject (ID do usuário)
            'iat' => time(), // Time when JWT was issued
            'exp' => time() + 60 * 60 // Expiration time
        ];

        $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        return response()->json(['data' => $jwt], Response::HTTP_CREATED);
    }
}
