<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'book_id',
        'cart_sesstion_id',
        'quantity',
        'created_at',
        'updated_at',
    ];
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
