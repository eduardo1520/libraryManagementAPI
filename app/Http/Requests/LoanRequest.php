<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;

class LoanRequest
{
    public static function validateId($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            [
                'id' => 'required|integer|exists:loans,id',
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
                'user_id'   => 'required|integer|exists:users,id',
                'book_id'     => 'required|integer|exists:books,id',
                'loan_date'   => 'required|date',
                'return_date' => 'required|date',
            ]
        );

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        return null;
    }
}
