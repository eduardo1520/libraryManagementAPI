<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{

    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'date_birth',
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];


    // Definindo o Accessor para 'date_birth'
    public function getDateBirthAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

     // One-to-many relationship: An author can have many books
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
