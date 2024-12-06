<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title', 'year_published', 'author_id'
    ];

    protected $dates = ['deleted_at'];

    // Many-to-one relationship: A book belongs to one author
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    // One-to-many relationship: A book can have many loans
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
