<?php

namespace App\Helpers;
class PasswordHelper
{
    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
