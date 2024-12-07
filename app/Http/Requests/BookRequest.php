<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;

class BookRequest
{
    public static function validateId($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            [
                'id' => 'required|integer|exists:books,id',
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
                'title' => 'required|string',
                'year_published' => 'required|integer',
                'author_id' => 'required|integer|exists:books,id',
            ]
        );

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        return null;
    }
}
