<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'book_id', 'loan_date', 'return_date'
    ];

    protected $dates = ['deleted_at'];

    // Many-to-one relationship: A loan belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Many-to-one relationship: A loan belongs to one book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
