<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'book_id',
        'description',
        'author',
        'publisher',
        'publish_year',
        'language',
        'page_number',
        'size',
        'weight',
    ];
}
