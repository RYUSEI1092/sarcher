<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Rental extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'isbn',
        'library',
        'rental_on',
        'return_on',
        'returned_at',
        'book_img',
        'book_title',
        'book_genre',
        'book_author',
        'publisher_name',
    ];

    public $timestamps = false;
}
