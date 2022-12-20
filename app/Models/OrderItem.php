<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_detail_id',
        'book_id',
        'quantity',
        'discount',
        'price',
    ];
    // book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
