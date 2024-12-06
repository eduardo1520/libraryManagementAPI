<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;

class AuthorRequest
{
    public static function validateId($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            [
                'id' => 'required|integer|exists:authors,id',
            ]
        );

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        return null;
    }

    public static function validateCreate($data)
    {
        $validator = Validator::make(
            $data,
            [
                'name' => 'required|string',
                'date_birth' => 'required|date',
            ]
        );

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        return null;
    }
}
