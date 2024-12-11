<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;

class UserRequest
{
    public static function validateUser(string $name, string $email, string $password)
    {
        $validator = Validator::make(
            ['name' => $name],
            ['email' => $email],
            ['password' => $password],
            [
                'name' => 'required|string|exists:users,name',
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        return null;
    }

}
