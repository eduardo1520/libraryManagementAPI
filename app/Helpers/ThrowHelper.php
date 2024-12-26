<?php

namespace App\Helpers;

use Exception;
use Illuminate\Http\Response;

class ThrowHelper
{
    public static function exception($message): void
    {
        throw new Exception($message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
